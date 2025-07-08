<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\DepartmentWithThisNameAlreadyExists;
use App\Domain\Department\Repository\DepartmentRepository;

class CreateDepartmentService
{
    public function __construct(private DepartmentRepository $departmentRepository)
    {
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public function create(string $name): Department
    {
        $isDepartmentWithNameExist = $this->departmentRepository->isDepartmentWithNameExists($name);

        if ($isDepartmentWithNameExist) {
            throw new DepartmentWithThisNameAlreadyExists();
        }

        $department = new Department($name);

        $this->departmentRepository->add($department);

        return $department;
    }
}
