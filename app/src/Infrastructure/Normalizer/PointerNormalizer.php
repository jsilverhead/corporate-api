<?php

declare(strict_types=1);

namespace App\Infrastructure\Normalizer;

use Spiks\UserInputProcessor\Pointer;

class PointerNormalizer
{
    public function normalizeJsonPointer(Pointer $pointer): string
    {
        return '/' . implode('/', $pointer->getPropertyPath());
    }
}
