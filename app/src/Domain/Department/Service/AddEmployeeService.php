<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\EmployeeAlreadyInTheDepartmentException;
use App\Domain\Employee\Employee;

class AddEmployeeService
{
    public function add(Employee $employee, Department $department): void
    {
        if ($employee->department?->id->equals($department->id)) {
            throw new EmployeeAlreadyInTheDepartmentException();
        }

        $department->employees->add($employee);
        $employee->department = $department;
    }
}
