<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class UserAlreadySupervisingThisDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'User already supervising this department';
    }

    public function getType(): string
    {
        return 'user_already_supervising_this_department';
    }
}
