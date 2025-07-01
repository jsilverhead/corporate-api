<?php

declare(strict_types=1);

namespace App\Infrastructure\Payload\Exception;

use App\Domain\Common\Exception\ServiceException;

class InvalidAuthorizationHeaderException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Invalid authorization header.';
    }

    public function getType(): string
    {
        return 'invalid_authorization_header';
    }
}
