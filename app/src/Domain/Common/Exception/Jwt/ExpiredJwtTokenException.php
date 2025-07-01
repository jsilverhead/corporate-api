<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception\Jwt;

use App\Domain\Common\Exception\ServiceException;

class ExpiredJwtTokenException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Jwt token has expired.';
    }

    public function getType(): string
    {
        return 'expired_jwt_token';
    }
}
