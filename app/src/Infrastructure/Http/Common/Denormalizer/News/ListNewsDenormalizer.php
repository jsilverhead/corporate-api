<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\News;

use App\Domain\Common\ValueObject\Pagination;
use App\Domain\News\Enum\NewsOrderDirectionEnum;
use App\Infrastructure\Denormalizer\PaginationDenormalizer;
use App\Infrastructure\Http\Common\Dto\News\ListNewsDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\EnumerationDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

readonly class ListNewsDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private PaginationDenormalizer $paginationDenormalizer,
        private EnumerationDenormalizer $enumerationDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): ListNewsDto
    {
        /**
         * @psalm-var array{
         *     pagination: Pagination,
         *     orderByDate: NewsOrderDirectionEnum
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
                'orderByDate' => new ObjectField(
                    fn(
                        mixed $data,
                        Pointer $pointer,
                    ): NewsOrderDirectionEnum => $this->enumerationDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        enumClassName: NewsOrderDirectionEnum::class,
                    ),
                ),
            ],
        );

        return new ListNewsDto(
            pagination: $denormalizedData['pagination'],
            orderDirection: $denormalizedData['orderByDate'],
        );
    }
}
