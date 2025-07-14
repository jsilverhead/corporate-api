<?php

declare(strict_types=1);

namespace App\Domain\Employee\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeAlreadyDeletedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Employee already deleted.';
    }

    public function getType(): string
    {
        return 'employee_already_deleted';
    }
}
