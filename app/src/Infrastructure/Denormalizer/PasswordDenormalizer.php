<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Pointer;

readonly class PasswordDenormalizer
{
    public const PASSWORD_MAX_LENGTH = 255;
    public const PASSWORD_MIN_LENGTH = 8;

    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    public function denormalize(mixed $data, Pointer $pointer): string
    {
        return $this->stringDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            minLength: self::PASSWORD_MIN_LENGTH,
            maxLength: self::PASSWORD_MAX_LENGTH,
        );
    }
}
