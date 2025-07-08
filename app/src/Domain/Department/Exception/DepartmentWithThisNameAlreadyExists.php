<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class DepartmentWithThisNameAlreadyExists extends ServiceException
{
    public function getDescription(): string
    {
        return 'Department with this name already exists';
    }

    public function getType(): string
    {
        return 'department_with_this_name_already_exists';
    }
}
