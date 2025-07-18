<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Survey;

use App\Infrastructure\Denormalizer\NameDenormalizer;
use App\Infrastructure\Denormalizer\QuestionsDenormalizer;
use App\Infrastructure\Http\Common\Dto\Survey\CreateSurveyTemplateDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class CreateSurveyTemplateDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private QuestionsDenormalizer $questionsDenormalizer,
        private NameDenormalizer $nameDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): CreateSurveyTemplateDto
    {
        /**
         * @psalm-var array{
         *     name: non-empty-string,
         *     questions: list<non-empty-string>
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'name' => new ObjectField(
                    fn(mixed $data, Pointer $pointer) => $this->nameDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'questions' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): array => $this->questionsDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new CreateSurveyTemplateDto(name: $denormalizedData['name'], questions: $denormalizedData['questions']);
    }
}
