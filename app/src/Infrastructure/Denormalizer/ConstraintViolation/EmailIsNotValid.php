<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer\ConstraintViolation;

use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Spiks\UserInputProcessor\Pointer;

/**
 * @psalm-suppress
 */
readonly class EmailIsNotValid implements ConstraintViolationInterface
{
    public function __construct(private Pointer $pointer)
    {
    }

    public static function getType(): string
    {
        return 'email_is_not_valid';
    }

    public function getDescription(): string
    {
        return 'Email has invalid format.';
    }

    public function getPointer(): Pointer
    {
        return $this->pointer;
    }
}
