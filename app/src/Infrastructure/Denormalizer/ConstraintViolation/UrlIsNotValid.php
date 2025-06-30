<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer\ConstraintViolation;

use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Spiks\UserInputProcessor\Pointer;

readonly class UrlIsNotValid implements ConstraintViolationInterface
{
    public function __construct(private Pointer $pointer)
    {
    }

    public static function getType(): string
    {
        return 'url_is_not_valid';
    }

    public function getDescription(): string
    {
        return 'Url has invalid format.';
    }

    public function getPointer(): Pointer
    {
        return $this->pointer;
    }
}
