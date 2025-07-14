<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Employee;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Service\CreateEmployeeService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Employee\CreateEmployeeDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\Employee\CreateEmployeeNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/createEmployee', methods: [Request::METHOD_POST])]
readonly class CreateEmployee
{
    public function __construct(
        private Responder $responder,
        private CreateEmployeeService $createUserService,
        private CreateEmployeeDenormalizer $createEmployeeDenormalizer,
        private EntityManagerInterface $entityManager,
        private CreateEmployeeNormalizer $createEmployeeNormalizer,
        private DepartmentRepository $departmentRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        // TODO: Добавить MessageBusInterface + генерацию письма
        $dto = $this->createEmployeeDenormalizer->denormalize($payload);

        $department = null;

        if (null !== $dto->departmentId) {
            $department = $this->departmentRepository->getByIdOrFail($dto->departmentId);
        }

        $supervising = null;

        if (null !== $dto->supervisingId) {
            $supervising = $this->departmentRepository->getByIdOrFail($dto->supervisingId);
        }

        $employee = $this->createUserService->create(
            name: $dto->name,
            email: $dto->email,
            password: $dto->password,
            role: $dto->role,
            birthDate: $dto->birthDate,
            department: $department,
            supervising: $supervising,
        );

        $this->entityManager->flush();

        $normalizedData = $this->createEmployeeNormalizer->normalize($employee);

        return $this->responder->success($normalizedData);
    }
}
