<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\EmployeeIsNotInTheDepartmentException;
use App\Domain\Employee\Employee;

class RemoveEmployeeService
{
    public function remove(Department $department, Employee $employee): void
    {
        if (!$employee->department?->id->equals($department->id)) {
            throw new EmployeeIsNotInTheDepartmentException();
        }

        $employee->department = null;
        $department->employees->removeElement($employee);
    }
}
