<?php

declare(strict_types=1);

namespace App\Domain\Employee\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeIsNotSupervisingAnyDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Employee is not supervising any department';
    }

    public function getType(): string
    {
        return 'employee_is_not_supervising_any_department';
    }
}
