<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Department\Service\UpdateDepartmentService;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\UpdateDepartmentDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/updateDepartment', methods: [Request::METHOD_POST])]
class UpdateDepartment
{
    public function __construct(
        private Responder $responder,
        private EntityManagerInterface $entityManager,
        private UpdateDepartmentService $updateDepartmentService,
        private UpdateDepartmentDenormalizer $updateDepartmentDenormalizer,
        private DepartmentRepository $departmentRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $user, Payload $payload): Response
    {
        $dto = $this->updateDepartmentDenormalizer->denormalize($payload);

        $department = $this->departmentRepository->getByIdOrFail($dto->id);

        $this->updateDepartmentService->update(department: $department, name: $dto->name);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
