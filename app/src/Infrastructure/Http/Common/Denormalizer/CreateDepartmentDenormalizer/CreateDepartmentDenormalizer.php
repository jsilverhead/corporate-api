<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\CreateDepartmentDenormalizer;

use App\Infrastructure\Denormalizer\NameDenormalizer;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class CreateDepartmentDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private NameDenormalizer $nameDenormalizer,
    ) {
    }

    /**
     * @psalm-return non-empty-string
     */
    public function denormalize(Payload $payload): string
    {
        /**
         * @psalm-var array{
         *     name: non-empty-string
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'name' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->nameDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return $denormalizedData['name'];
    }
}
