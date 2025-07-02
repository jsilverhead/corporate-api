<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Auth;

use App\Domain\Common\ValueObject\Email;
use App\Infrastructure\Denormalizer\EmailDenormalizer;
use App\Infrastructure\Denormalizer\PasswordDenormalizer;
use App\Infrastructure\Http\Common\Dto\Auth\IssueAccessTokenByEmailAndPasswordDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class IssueAccessTokenByEmailAndPasswordDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private EmailDenormalizer $emailDenormalizer,
        private PasswordDenormalizer $passwordDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): IssueAccessTokenByEmailAndPasswordDto
    {
        /**
         * @psalm-var array{
         *     email: Email,
         *     password: non-empty-string,
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
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
            ],
        );

        return new IssueAccessTokenByEmailAndPasswordDto(
            email: $denormalizedData['email'],
            password: $denormalizedData['password'],
        );
    }
}
