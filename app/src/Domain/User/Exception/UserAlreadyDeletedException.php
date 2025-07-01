<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use App\Domain\Common\Exception\ServiceException;

class UserAlreadyDeletedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'User already deleted.';
    }

    public function getType(): string
    {
        return 'user_already_deleted';
    }
}
