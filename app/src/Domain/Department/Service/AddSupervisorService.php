<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\UserAlreadySupervisingThisDepartmentException;
use App\Domain\User\User;

class AddSupervisorService
{
    public function add(User $supervisor, Department $department): void
    {
        if ($supervisor->supervising?->id->equals($department->id)) {
            throw new UserAlreadySupervisingThisDepartmentException();
        }

        $department->supervisors->add($supervisor);
        $supervisor->supervising = $department;
    }
}
