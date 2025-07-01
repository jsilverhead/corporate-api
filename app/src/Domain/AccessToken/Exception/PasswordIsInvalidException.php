<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Exception;

use App\Domain\Common\Exception\ServiceException;

class PasswordIsInvalidException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Password is invalid.';
    }

    public function getType(): string
    {
        return 'password_is_invalid';
    }
}
