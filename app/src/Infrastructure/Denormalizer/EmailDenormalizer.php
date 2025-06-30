<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Common\Exception\EmailIsInvalidException;
use App\Domain\Common\ValueObject\Email;
use App\Infrastructure\Denormalizer\ConstraintViolation\EmailIsNotValid;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

readonly class EmailDenormalizer
{
    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    public function denormalize(mixed $data, Pointer $pointer): Email
    {
        try {
            /** @psalm-var non-empty-string $emailString */
            $emailString = $this->stringDenormalizer->denormalize($data, $pointer, minLength: 1);
            $email = Email::tryCreateFromString($emailString);
        } catch (ValidationError | EmailIsInvalidException) {
            throw new ValidationError([new EmailIsNotValid($pointer)]);
        }

        return $email;
    }
}
