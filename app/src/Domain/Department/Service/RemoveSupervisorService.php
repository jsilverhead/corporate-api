<?php

declare(strict_types=1);

namespace App\Domain\Department\Service;

use App\Domain\Department\Department;
use App\Domain\Department\Exception\UserDoNotSupervisingThisDepartmentException;
use App\Domain\User\User;

class RemoveSupervisorService
{
    public function remove(Department $department, User $supervisor): void
    {
        if (!$supervisor->supervising?->id->equals($department->id)) {
            throw new UserDoNotSupervisingThisDepartmentException();
        }

        $department->supervisors->removeElement($supervisor);
        $supervisor->supervising = null;
    }
}
