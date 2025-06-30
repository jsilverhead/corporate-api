<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer\ConstraintViolation;

use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Spiks\UserInputProcessor\Pointer;

readonly class UuidIsNotValid implements ConstraintViolationInterface
{
    public function __construct(private Pointer $pointer)
    {
    }

    public static function getType(): string
    {
        return 'uuid_is_not_valid';
    }

    public function getDescription(): string
    {
        return 'UUID has invalid format';
    }

    public function getPointer(): Pointer
    {
        return $this->pointer;
    }
}
