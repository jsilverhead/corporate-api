<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Common\Exception\PhoneNumberIsInvalidException;
use App\Domain\Common\ValueObject\Phone;
use App\Infrastructure\Denormalizer\ConstraintViolation\PhoneIsNotValid;
use Spiks\UserInputProcessor\Denormalizer\StringDenormalizer;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

readonly class PhoneDenormalizer
{
    public function __construct(private StringDenormalizer $stringDenormalizer)
    {
    }

    /**
     * It expects `$data` to be string of phone number (https://en.wikipedia.org/wiki/E.164).
     */
    public function denormalize(mixed $data, Pointer $pointer): Phone
    {
        try {
            /** @psalm-var non-empty-string $phoneString */
            $phoneString = $this->stringDenormalizer->denormalize($data, $pointer, pattern: '#^\+[0-9]{7,15}$#');
            $phone = Phone::tryCreateFromString($phoneString);
        } catch (ValidationError | PhoneNumberIsInvalidException) {
            throw new ValidationError([new PhoneIsNotValid($pointer)]);
        }

        return $phone;
    }
}
