<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeAlreadyInTheDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'The employee is already in the department';
    }

    public function getType(): string
    {
        return 'employee_already_in_the_department';
    }
}
