<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

use DomainException;

abstract class ServiceException extends DomainException
{
    /**
     * @psalm-return non-empty-string
     */
    abstract public function getDescription(): string;

    /**
     * @psalm-return non-empty-string
     */
    abstract public function getType(): string;

    /**
     * @psalm-return array<string, mixed>
     */
    public function getAdditionalFields(): array
    {
        return [];
    }
}
