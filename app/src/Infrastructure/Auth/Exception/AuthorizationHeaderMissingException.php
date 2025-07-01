<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Exception;

use App\Domain\Common\Exception\ServiceException;

class AuthorizationHeaderMissingException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Authorization header missing.';
    }

    public function getType(): string
    {
        return 'authorization_header_missing';
    }
}
