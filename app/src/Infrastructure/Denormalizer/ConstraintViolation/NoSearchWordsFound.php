<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer\ConstraintViolation;

use Spiks\UserInputProcessor\ConstraintViolation\ConstraintViolationInterface;
use Spiks\UserInputProcessor\Pointer;

readonly class NoSearchWordsFound implements ConstraintViolationInterface
{
    public function __construct(private Pointer $pointer)
    {
    }

    public static function getType(): string
    {
        return 'no_search_words_found';
    }

    public function getDescription(): string
    {
        return 'No search words found.';
    }

    public function getPointer(): Pointer
    {
        return $this->pointer;
    }
}
