<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Department\Service\RemoveEmployeeService;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\RemoveEmployeeDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/removeEmployee', methods: [Request::METHOD_POST])]
readonly class RemoveEmployee
{
    public function __construct(
        private Responder $responder,
        private RemoveEmployeeDenormalizer $removeEmployeeDenormalizer,
        private RemoveEmployeeService $removeEmployeeService,
        private EntityManagerInterface $entityManager,
        private EmployeeRepository $employeeRepository,
        private DepartmentRepository $departmentRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $user, Payload $payload): Response
    {
        $dto = $this->removeEmployeeDenormalizer->denormalize($payload);
        $employee = $this->employeeRepository->getByIdOrFail($dto->employeeId);
        $department = $this->departmentRepository->getByIdOrFail($dto->departmentId);

        $this->removeEmployeeService->remove(department: $department, employee: $employee);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
