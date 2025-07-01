<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Exception;

use App\Domain\Common\Exception\ServiceException;

class ExpiredAccessTokenException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Expired access token.';
    }

    public function getType(): string
    {
        return 'expired_access_token';
    }
}
