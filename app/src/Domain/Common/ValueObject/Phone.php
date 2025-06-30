<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Exception\PhoneNumberIsInvalidException;

readonly class Phone
{
    private const PHONE_PATTERN = '#^\+[0-9]{7,15}$#';

    /**
     * @psalm-param non-empty-string $phone
     */
    private function __construct(public string $phone)
    {
    }

    /**
     * @psalm-param non-empty-string $phone
     *
     * @throws PhoneNumberIsInvalidException
     */
    public static function tryCreateFromString($phone): self
    {
        if (1 !== preg_match(self::PHONE_PATTERN, $phone)) {
            throw new PhoneNumberIsInvalidException();
        }

        return new self($phone);
    }

    public function __toString(): string
    {
        return $this->phone;
    }
}
