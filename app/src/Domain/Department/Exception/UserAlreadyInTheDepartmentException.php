<?php

declare(strict_types=1);

namespace App\Domain\Department\Exception;

use App\Domain\Common\Exception\ServiceException;

class UserAlreadyInTheDepartmentException extends ServiceException
{
    public function getDescription(): string
    {
        return 'The user is already in the department';
    }

    public function getType(): string
    {
        return 'user_already_in_the_department';
    }
}
