<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Exception;

use App\Domain\Common\Exception\ServiceException;

class ExpiredJwtTokenException extends ServiceException
{
    public function getDescription(): string
    {
        return 'JWT token has expired';
    }

    public function getType(): string
    {
        return 'jwt_token_has_expired';
    }
}
