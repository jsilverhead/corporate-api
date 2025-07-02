<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Infrastructure\Denormalizer\ConstraintViolation\NoSearchWordsFound;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

class SearchWordsDenormalizer
{
    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    /**
     * @psalm-return list<string>
     */
    public function denormalize(mixed $data, Pointer $pointer): array
    {
        $denormalizedString = $this->stringDenormalizer->denormalize(data: $data, pointer: $pointer);

        $searchWords = preg_split('/[^\p{L}T]+/u', $denormalizedString, -1, \PREG_SPLIT_NO_EMPTY);

        if (false === $searchWords || [] === $searchWords) {
            throw new ValidationError([new NoSearchWordsFound(pointer: $pointer)]);
        }

        return $searchWords;
    }
}
