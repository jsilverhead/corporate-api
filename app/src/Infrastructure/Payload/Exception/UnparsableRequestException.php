<?php

declare(strict_types=1);

namespace App\Infrastructure\Payload\Exception;

use App\Domain\Common\Exception\ServiceException;

class UnparsableRequestException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Invalid JSON payload';
    }

    public function getType(): string
    {
        return 'invalid_json_payload';
    }
}
