<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\UserAlreadyInTheDepartmentException;
use App\Domain\User\User;

class AddEmployeeService
{
    public function add(User $employee, Department $department): void
    {
        if ($employee->department?->id->equals($department->id)) {
            throw new UserAlreadyInTheDepartmentException();
        }

        $department->employees->add($employee);
        $employee->department = $department;
    }
}
