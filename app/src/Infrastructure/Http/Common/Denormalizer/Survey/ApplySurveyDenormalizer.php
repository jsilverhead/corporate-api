<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Survey;

use App\Domain\Survey\Dto\AnswerDataDto;
use App\Infrastructure\Denormalizer\AnswerDataDenormalizer;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Dto\Survey\ApplySurveyDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

readonly class ApplySurveyDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
        private AnswerDataDenormalizer $answerDataDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): ApplySurveyDto
    {
        /**
         * @psalm-var array{
         *     surveyId: Uuid,
         *     answers: list<AnswerDataDto>
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'surveyId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'answers' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): array => $this->answerDataDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new ApplySurveyDto(surveyId: $denormalizedData['surveyId'], answerData: $denormalizedData['answers']);
    }
}
