<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\EmployeeDoNotSuperviseThisDepartmentException;
use App\Domain\Employee\Employee;

class RemoveSupervisorService
{
    public function remove(Department $department, Employee $supervisor): void
    {
        if (!$supervisor->supervising?->id->equals($department->id)) {
            throw new EmployeeDoNotSuperviseThisDepartmentException();
        }

        $department->supervisors->removeElement($supervisor);
        $supervisor->supervising = null;
    }
}
