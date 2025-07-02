<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Common\ValueObject\Pagination;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class PaginationDenormalizer
{
    public function __construct(
        public ObjectDenormalizer $objectDenormalizer,
        public NumericStringDenormalizer $numericStringDenormalizer,
    ) {
    }

    /**
     * @psalm-param int<1,50> $maxCount
     */
    public function denormalize(mixed $data, Pointer $pointer, int $maxCount = 50): Pagination
    {
        /**
         * @psalm-var array{
         *     count: int<1,50>,
         *     offset: int<0,max>
         * } $denormalizedPagination
         */
        $denormalizedPagination = $this->objectDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            fieldDenormalizers: [
                'count' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): int => $this->numericStringDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        maximum: $maxCount,
                        minimum: 1,
                    ),
                ),
                'offset' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): int => $this->numericStringDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        minimum: 0,
                    ),
                ),
            ],
        );

        return new Pagination(count: $denormalizedPagination['count'], offset: $denormalizedPagination['offset']);
    }
}
