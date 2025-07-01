<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\User;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use App\Infrastructure\Denormalizer\EmailDenormalizer;
use App\Infrastructure\Denormalizer\NameDenormalizer;
use App\Infrastructure\Denormalizer\PasswordDenormalizer;
use App\Infrastructure\Http\Common\Dto\User\CreateUserDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\EnumerationDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

readonly class CreateUserDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private EmailDenormalizer $emailDenormalizer,
        private PasswordDenormalizer $passwordDenormalizer,
        private NameDenormalizer $nameDenormalizer,
        private EnumerationDenormalizer $enumerationDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): CreateUserDto
    {
        /**
         * @psalm-var array{
         *     name: non-empty-string,
         *     email: Email,
         *     password: non-empty-string,
         *     role: RolesEnum
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
                'email' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Email => $this->emailDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'password' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): string => $this->passwordDenormalizer->denormalize(
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

        return new CreateUserDto(
            name: $denormalizedData['name'],
            email: $denormalizedData['email'],
            password: $denormalizedData['password'],
            role: $denormalizedData['role'],
        );
    }
}
