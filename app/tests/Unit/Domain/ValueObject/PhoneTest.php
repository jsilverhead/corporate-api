<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\Common\Exception\PhoneNumberIsInvalidException;
use App\Domain\Common\ValueObject\Phone;
use App\Tests\BaseWebTestCase;
use Exception;

/**
 * @internal
 *
 * @covers \App\Domain\Common\ValueObject\Phone
 */
final class PhoneTest extends BaseWebTestCase
{
    /**
     * @psalm-return list<array<non-empty-string>>
     */
    public static function provideSuccessfulCreatingCases(): iterable
    {
        return [['+711122334444'], ['+77711122334444'], ['+12233344455']];
    }

    /**
     * @psalm-return list<array<non-empty-string>>
     */
    public static function provideUnsuccessfulCreatingCases(): iterable
    {
        return [['+7O11122334444'], ['+777'], ['+122333444455555666666']];
    }

    /**
     * @dataProvider provideSuccessfulCreatingCases
     *
     * @psalm-param non-empty-string $payload
     */
    public function testSuccessfulCreating(string $payload): void
    {
        $phone = Phone::tryCreateFromString($payload);

        self::assertSame($payload, (string) $phone);
    }

    /**
     * @dataProvider provideUnsuccessfulCreatingCases
     *
     * @psalm-param non-empty-string $payload
     */
    public function testUnsuccessfulCreating(string $payload): void
    {
        try {
            Phone::tryCreateFromString($payload);
            self::fail('Expected exception to be thrown.');
        } catch (Exception $exception) {
            self::assertInstanceOf(PhoneNumberIsInvalidException::class, $exception);
        }
    }
}
