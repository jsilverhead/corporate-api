<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

class AccessIsDeniedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Access is denied.';
    }

    public function getType(): string
    {
        return 'access_is_denied';
    }
}
