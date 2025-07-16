<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Department\Service\DeleteDepartmentService;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\DeleteDepartmentDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route(path: '/deleteDepartment', methods: [Request::METHOD_POST])]
readonly class DeleteDepartment
{
    public function __construct(
        private Responder $responder,
        private DeleteDepartmentDenormalizer $deleteDepartmentDenormalizer,
        private EntityManagerInterface $entityManager,
        private DeleteDepartmentService $deleteDepartmentService,
        private DepartmentRepository $departmentRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $id = $this->deleteDepartmentDenormalizer->denormalize($payload);
        $department = $this->departmentRepository->getByIdOrFail($id);

        $this->entityManager->getConnection();
        $this->entityManager->beginTransaction();

        try {
            $this->deleteDepartmentService->delete($department);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        return $this->responder->success();
    }
}
