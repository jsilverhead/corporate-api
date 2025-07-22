<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Survey;

use App\Domain\Common\ValueObject\Pagination;
use App\Domain\Survey\Enum\StatusEnum;
use App\Infrastructure\Denormalizer\PaginationDenormalizer;
use App\Infrastructure\Http\Common\Dto\Survey\ListSurveysDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\EnumerationDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class ListSurveysDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private PaginationDenormalizer $paginationDenormalizer,
        private EnumerationDenormalizer $enumerationDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): ListSurveysDto
    {
        /**
         * @psalm-var array{
         *     pagination: Pagination,
         *     status: StatusEnum
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'pagination' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Pagination => $this->paginationDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'status' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): StatusEnum => $this->enumerationDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        enumClassName: StatusEnum::class,
                    ),
                ),
            ],
        );

        return new ListSurveysDto(pagination: $denormalizedData['pagination'], status: $denormalizedData['status']);
    }
}
