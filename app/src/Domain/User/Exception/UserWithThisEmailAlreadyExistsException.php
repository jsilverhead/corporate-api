<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use App\Domain\Common\Exception\ServiceException;

class UserWithThisEmailAlreadyExistsException extends ServiceException
{
    public function getDescription(): string
    {
        return 'User with this email already exists.';
    }

    public function getType(): string
    {
        return 'user_with_this_email_already_exists';
    }
}
