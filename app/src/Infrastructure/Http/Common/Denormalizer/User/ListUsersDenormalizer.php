<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\User;

use App\Domain\Common\ValueObject\Pagination;
use App\Infrastructure\Denormalizer\PaginationDenormalizer;
use App\Infrastructure\Denormalizer\SearchWordsDenormalizer;
use App\Infrastructure\Http\Common\Dto\User\ListUsersDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class ListUsersDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private PaginationDenormalizer $paginationDenormalizer,
        private SearchWordsDenormalizer $searchWordsDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): ListUsersDto
    {
        /**
         * @psalm-var array{
         *     pagination: Pagination,
         *     filter?: array {
         *         search: list<string>
         *     }
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
                'filter' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): array => $this->objectDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        fieldDenormalizers: [
                            'search' => new ObjectField(
                                fn(mixed $data, Pointer $pointer): array => $this->searchWordsDenormalizer->denormalize(
                                    data: $data,
                                    pointer: $pointer,
                                ),
                            ),
                        ],
                    ),
                    isMandatory: false,
                ),
            ],
        );

        $searchWords = null;

        if (\array_key_exists('filter', $denormalizedData)) {
            /**
             * @psalm-var list<string>|null $searchWords
             */
            $searchWords = $denormalizedData['filter']['search'];
        }

        return new ListUsersDto(pagination: $denormalizedData['pagination'], searchWords: $searchWords);
    }
}
