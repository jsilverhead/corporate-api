<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Department;

use App\Infrastructure\Denormalizer\NameDenormalizer;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Dto\Department\UpdateDepartmentDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

class UpdateDepartmentDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
        private NameDenormalizer $nameDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): UpdateDepartmentDto
    {
        /**
         * @psalm-var array{
         *     id: Uuid,
         *     name: non-empty-string
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'id' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'name' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->nameDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new UpdateDepartmentDto(id: $denormalizedData['id'], name: $denormalizedData['name']);
    }
}
