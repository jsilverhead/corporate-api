<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Survey;

use App\Infrastructure\Denormalizer\QuestionsDenormalizer;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class CreateSurveyTemplateDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private QuestionsDenormalizer $questionsDenormalizer,
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
                    fn(mixed $data, Pointer $pointer): array => $this->questionsDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return $denormalizedData['questions'];
    }
}
