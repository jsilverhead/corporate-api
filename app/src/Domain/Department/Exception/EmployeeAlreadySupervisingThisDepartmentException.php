<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeAlreadySupervisingThisDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'The employee already supervising this department';
    }

    public function getType(): string
    {
        return 'employee_already_supervising_this_department';
    }
}
