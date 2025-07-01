<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Pointer;

readonly class NameDenormalizer
{
    public const NAME_MAX_LENGTH = 255;
    public const NAME_MIN_LENGTH = 3;

    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    public function denormalize(mixed $data, Pointer $pointer): string
    {
        return $this->stringDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            minLength: self::NAME_MIN_LENGTH,
            maxLength: self::NAME_MAX_LENGTH,
        );
    }
}
