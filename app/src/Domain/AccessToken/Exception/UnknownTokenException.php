<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Exception;

use App\Domain\Common\Exception\ServiceException;

class UnknownTokenException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Unknown token.';
    }

    public function getType(): string
    {
        return 'unknown_token';
    }
}
