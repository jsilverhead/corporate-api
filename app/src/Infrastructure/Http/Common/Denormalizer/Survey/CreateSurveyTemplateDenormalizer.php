<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Survey;

use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ArrayDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class CreateSurveyTemplateDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private ArrayDenormalizer $arrayDenormalizer,
        private StringDenormalizer $stringDenormalizer,
    ) {
    }

    /**
     * @psalm-return list<non-empty-string>
     */
    public function denormalize(Payload $payload): array
    {
        /**
         * @psalm-var array{
         *     questions: list<non-empty-string>
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'questions' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): array => $this->arrayDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        denormalizer: fn(
                            mixed $data,
                            Pointer $pointer,
                        ): string => $this->stringDenormalizer->denormalize(
                            data: $data,
                            pointer: $pointer,
                            minLength: 1,
                            maxLength: 255,
                        ),
                        minItems: 1,
                        maxItems: 30,
                    ),
                ),
            ],
        );

        return $denormalizedData['questions'];
    }
}
