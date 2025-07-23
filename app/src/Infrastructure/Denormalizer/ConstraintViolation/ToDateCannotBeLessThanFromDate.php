<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer\ConstraintViolation;

use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Spiks\UserInputProcessor\Pointer;

readonly class ToDateCannotBeLessThanFromDate implements ConstraintViolationInterface
{
    public function __construct(private Pointer $pointer)
    {
    }

    public static function getType(): string
    {
        return 'from_date_cannot_be_less_than_to_date';
    }

    public function getDescription(): string
    {
        return 'From date cannot be less than to_date';
    }

    public function getPointer(): Pointer
    {
        return $this->pointer;
    }
}
