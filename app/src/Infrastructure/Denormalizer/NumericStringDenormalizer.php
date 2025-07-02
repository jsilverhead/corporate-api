<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use Spiks\UserInputProcessor\ConstraintViolation\WrongPropertyType;
use Spiks\UserInputProcessor\Denormalizer\IntegerDenormalizer;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

class NumericStringDenormalizer
{
    public function __construct(private IntegerDenormalizer $integerDenormalizer)
    {
    }

    public function denormalize(mixed $data, Pointer $pointer, ?int $maximum = null, ?int $minimum = null): int
    {
        if (!\is_string($data)) {
            throw new ValidationError([
                WrongPropertyType::guessGivenType($pointer, $data, [WrongPropertyType::JSON_TYPE_STRING]),
            ]);
        }

        if (!ctype_digit($data)) {
            throw new ValidationError([
                WrongPropertyType::GuessGivenType($pointer, $data, [WrongPropertyType::JSON_TYPE_NUMBER]),
            ]);
        }

        return $this->integerDenormalizer->denormalize(
            data: (int) $data,
            pointer: $pointer,
            minimum: $minimum,
            maximum: $maximum,
        );
    }
}
