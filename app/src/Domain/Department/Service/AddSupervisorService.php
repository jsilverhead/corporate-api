<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\EmployeeAlreadySupervisingThisDepartmentException;
use App\Domain\Employee\Employee;

class AddSupervisorService
{
    public function add(Employee $supervisor, Department $department): void
    {
        if ($supervisor->supervising?->id->equals($department->id)) {
            throw new EmployeeAlreadySupervisingThisDepartmentException();
        }

        $department->supervisors->add($supervisor);
        $supervisor->supervising = $department;
    }
}
