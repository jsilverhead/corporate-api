<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\Department;

use App\Domain\Department\Repository\DepartmentRepository;
use App\Domain\Department\Service\AddEmployeeService;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\Department\AddEmployeeDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/addEmployee', methods: [Request::METHOD_POST])]
readonly class AddEmployee
{
    public function __construct(
        private Responder $responder,
        private AddEmployeeDenormalizer $addEmployeeDenormalizer,
        private AddEmployeeService $addEmployeeService,
        private EntityManagerInterface $entityManager,
        private DepartmentRepository $departmentRepository,
        private EmployeeRepository $employeeRepository,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedParam
     */
    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $user, Payload $payload): Response
    {
        $dto = $this->addEmployeeDenormalizer->denormalize($payload);
        $employee = $this->employeeRepository->getByIdOrFail($dto->employeeId);
        $department = $this->departmentRepository->getByIdOrFail($dto->departmentId);

        $this->addEmployeeService->add(employee: $employee, department: $department);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
