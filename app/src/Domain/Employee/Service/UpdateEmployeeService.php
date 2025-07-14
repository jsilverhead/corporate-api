<?php

declare(strict_types=1);

namespace App\Domain\Employee\Service;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;

class UpdateEmployeeService
{
    /**
     * @psalm-param non-empty-string $name
     */
    public function update(Employee $employee, string $name, RolesEnum $role): void
    {
        $employee->name = $name;
        $employee->role = $role;
    }
}
