<?php

declare(strict_types=1);

namespace App\Infrastructure\Payload;

/**
 * @psalm-suppress PossiblyUnusedProperty
 */
class Header
{
    /**
     * @psalm-param non-empty-string|null $accessToken
     */
    public function __construct(public ?string $accessToken)
    {
    }
}
