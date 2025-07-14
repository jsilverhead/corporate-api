<?php

declare(strict_types=1);

namespace App\Domain\Employee\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeWithThisEmailAlreadyExistsException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Employee with this email already exists.';
    }

    public function getType(): string
    {
        return 'employee_with_this_email_already_exists';
    }
}
