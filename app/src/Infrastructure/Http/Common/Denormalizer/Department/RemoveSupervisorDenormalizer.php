<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Department;

use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Dto\Department\RemoveSupervisorDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

class RemoveSupervisorDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): RemoveSupervisorDto
    {
        /**
         * @psalm-var array{
         *     userId: Uuid,
         *     departmentId: Uuid
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'userId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'departmentId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new RemoveSupervisorDto(
            userId: $denormalizedData['userId'],
            departmentId: $denormalizedData['departmentId'],
        );
    }
}
