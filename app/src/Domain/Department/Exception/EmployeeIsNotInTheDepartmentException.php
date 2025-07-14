<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeIsNotInTheDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'The employee is not in the department';
    }

    public function getType(): string
    {
        return 'employee_is_not_in_the_department';
    }
}
