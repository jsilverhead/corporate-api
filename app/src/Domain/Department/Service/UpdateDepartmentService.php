<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\DepartmentWithThisNameAlreadyExists;
use App\Domain\Department\Repository\DepartmentRepository;

class UpdateDepartmentService
{
    public function __construct(private DepartmentRepository $departmentRepository)
    {
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public function update(Department $department, string $name): void
    {
        $isDepartmentWithNameExist = $this->departmentRepository->isDepartmentWithNameExists($name);

        if ($isDepartmentWithNameExist) {
            throw new DepartmentWithThisNameAlreadyExists();
        }

        $department->name = $name;
    }
}
