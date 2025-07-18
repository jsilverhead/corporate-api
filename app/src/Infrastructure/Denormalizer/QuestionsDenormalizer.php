<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use Spiks\UserInputProcessor\Denormalizer\ArrayDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Pointer;

readonly class QuestionsDenormalizer
{
    public function __construct(
        private ArrayDenormalizer $arrayDenormalizer,
        private StringDenormalizer $stringDenormalizer,
    ) {
    }

    /**
     * @psalm-return list<non-empty-string>
     */
    public function denormalize(mixed $data, Pointer $pointer): array
    {
        /**
         * @psalm-var list<non-empty-string> $denormalizedQuestions
         */
        $denormalizedQuestions = $this->arrayDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            denormalizer: fn(mixed $data, Pointer $pointer) => $this->stringDenormalizer->denormalize(
                data: $data,
                pointer: $pointer,
                minLength: 1,
                maxLength: 255,
            ),
            minItems: 1,
            maxItems: 30,
        );

        return $denormalizedQuestions;
    }
}
