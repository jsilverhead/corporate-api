<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class DepartmentAlreadyDeletedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Department already deleted';
    }

    public function getType(): string
    {
        return 'department_already_deleted';
    }
}
