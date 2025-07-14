<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeDoNotSuperviseThisDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'The employee do not supervise this department';
    }

    public function getType(): string
    {
        return 'employee_do_not_supervise_this_department';
    }
}
