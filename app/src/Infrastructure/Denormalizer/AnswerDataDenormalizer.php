<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Survey\Dto\AnswerDataDto;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\UniqueArrayDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

class AnswerDataDenormalizer
{
    public function __construct(
        private UuidDenormalizer $uuidDenormalizer,
        private StringDenormalizer $stringDenormalizer,
        private ObjectDenormalizer $objectDenormalizer,
        private UniqueArrayDenormalizer $uniqueArrayDenormalizer,
    ) {
    }

    public function denormalize(mixed $data, Pointer $pointer): array
    {
        return $this->uniqueArrayDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            denormalizer: fn(mixed $data, Pointer $pointer) => $this->denormalizeAnswer(data: $data, pointer: $pointer),
            uniqueKeyProvider: static fn(AnswerDataDto $answerData) => $answerData->questionId->toRfc4122(),
            minItems: 1,
            maxItems: 30,
        );
    }

    private function denormalizeAnswer(mixed $data, Pointer $pointer): AnswerDataDto
    {
        /**
         * @psalm-var array{
         *     questionId: Uuid,
         *     answer: non-empty-string,
         * } $denormalizedAnswerData
         */
        $denormalizedAnswerData = $this->objectDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            fieldDenormalizers: [
                'questionId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'answer' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->stringDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        minLength: 1,
                        maxLength: 255,
                    ),
                ),
            ],
        );

        return new AnswerDataDto(
            questionId: $denormalizedAnswerData['questionId'],
            answer: $denormalizedAnswerData['answer'],
        );
    }
}
