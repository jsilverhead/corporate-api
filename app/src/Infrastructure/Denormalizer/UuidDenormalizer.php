<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Infrastructure\Denormalizer\ConstraintViolation\UuidIsNotValid;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

readonly class UuidDenormalizer
{
    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    public function denormalize(mixed $data, Pointer $pointer): Uuid
    {
        $data = $this->stringDenormalizer->denormalize($data, $pointer);

        if (false === Uuid::isValid($data)) {
            throw new ValidationError([new UuidIsNotValid($pointer)]);
        }

        return new Uuid($data);
    }
}
