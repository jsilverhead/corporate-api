<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Common\Enum\CustomTypes;
use App\Domain\Common\Exception\EmailIsInvalidException;
use App\Domain\Common\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

/**
 * @psalm-suppress UnusedClass
 */
class EmailType extends Type
{
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Email) {
            throw new InvalidArgumentException(
                sprintf('Expected $value to be null or an object instance of %s class', Email::class),
            );
        }

        return $value->__toString();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Email
    {
        if (null === $value) {
            return null;
        }

        if (!\is_string($value)) {
            throw new InvalidArgumentException('Expected $value to be null or string');
        }

        if ('' === $value) {
            throw new InvalidArgumentException('Expected $value to be non empty string');
        }

        try {
            /* @psalm-var non-empty-string $value */
            return Email::tryCreateFromString($value);
        } catch (EmailIsInvalidException) {
            throw new InvalidArgumentException('Expected $value is email');
        }
    }

    public function getName(): string
    {
        return CustomTypes::EMAIL;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
