<?php

declare(strict_types=1);

namespace App\Infrastructure\Payload;

/**
 * @psalm-suppress PossiblyUnusedProperty
 */
class Payload
{
    /**
     * @psalm-param array $arguments
     */
    public function __construct(public array $arguments, public Header $header)
    {
    }
}
