<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\DepartmentAlreadyDeletedException;
use App\Domain\Employee\Repository\EmployeeRepository;
use DateTimeImmutable;

class DeleteDepartmentService
{
    public function __construct(private EmployeeRepository $employeeRepository)
    {
    }

    public function delete(Department $department): void
    {
        if (null !== $department->deletedAt) {
            throw new DepartmentAlreadyDeletedException();
        }

        $this->employeeRepository->eraseDepartmentAndSupervising($department);

        $department->employees->clear();
        $department->supervisors->clear();

        $department->name = uniqid('deleted_', true) . $department->name;
        $department->deletedAt = new DateTimeImmutable();
    }
}
