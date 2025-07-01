<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Exception;

use App\Domain\Common\Exception\ServiceException;

class JwtTokenIsInvalidException extends ServiceException
{
    public function getDescription(): string
    {
        return 'JWT token is invalid.';
    }

    public function getType(): string
    {
        return 'jwt_token_is_invalid';
    }
}
