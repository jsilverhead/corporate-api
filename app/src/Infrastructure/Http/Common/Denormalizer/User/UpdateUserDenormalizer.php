<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\User;

use App\Domain\User\Enum\RolesEnum;
use App\Infrastructure\Denormalizer\NameDenormalizer;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Dto\User\UpdateUserDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\EnumerationDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

readonly class UpdateUserDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
        private NameDenormalizer $nameDenormalizer,
        private EnumerationDenormalizer $enumerationDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): UpdateUserDto
    {
        /**
         * @psalm-var array{
         *     userId: Uuid,
         *     name: non-empty-string,
         *     role: RolesEnum,
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
                'name' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->nameDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'role' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): RolesEnum => $this->enumerationDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        enumClassName: RolesEnum::class,
                    ),
                ),
            ],
        );

        return new UpdateUserDto(
            userId: $denormalizedData['userId'],
            name: $denormalizedData['name'],
            role: $denormalizedData['role'],
        );
    }
}
