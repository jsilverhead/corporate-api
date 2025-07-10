<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class UserDoNotSupervisingThisDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'User do not supervising this department';
    }

    public function getType(): string
    {
        return 'user_do_not_supervising_this_department';
    }
}
