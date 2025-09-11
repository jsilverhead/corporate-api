<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Pointer;

class TitleDenormalizer
{
    private const MAX_LENGTH = 255;

    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    public function denormalize(mixed $data, Pointer $pointer): string
    {
        /** @psalm-var non-empty-string $denormalizedTitle */
        $denormalizedTitle = $this->stringDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            minLength: 5,
            maxLength: self::MAX_LENGTH,
        );

        return $denormalizedTitle;
    }
}
