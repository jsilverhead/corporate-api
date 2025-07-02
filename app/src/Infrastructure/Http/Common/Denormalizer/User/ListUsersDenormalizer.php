<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\User;

use App\Domain\Common\ValueObject\Pagination;
use App\Infrastructure\Denormalizer\PaginationDenormalizer;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class ListUsersDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private PaginationDenormalizer $paginationDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): Pagination
    {
        /**
         * @psalm-var array{
         *     pagination: Pagination
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
            ],
        );

        return $denormalizedData['pagination'];
    }
}
