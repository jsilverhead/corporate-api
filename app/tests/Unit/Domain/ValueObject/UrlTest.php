<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\Common\Exception\UrlIsInvalidException;
use App\Domain\Common\ValueObject\Url;
use App\Tests\BaseWebTestCase;
use Exception;

/**
 * @internal
 *
 * @covers \App\Domain\Common\ValueObject\Phone
 */
final class UrlTest extends BaseWebTestCase
{
    /**
     * @psalm-return list<array<non-empty-string>>
     */
    public static function provideSuccessfulCreatingCases(): iterable
    {
        return [
            ['http://google.com'],
            ['https://google.com'],
            ['http://www.google.com'],
            ['https://www.google.com'],
            ['http://www.google.com/search?q=bar'],
            ['https://www.google.com/search?q=foo'],
            ['//www.google.com'],
        ];
    }

    /**
     * @psalm-return list<array<non-empty-string>>
     */
    public static function provideUnsuccessfulCreatingCases(): iterable
    {
        return [['www.google.com'], ['www.google.com/search?q=bar'], ['google.com'], ['googlecom']];
    }

    /**
     * @dataProvider provideSuccessfulCreatingCases
     *
     * @psalm-param non-empty-string $payload
     */
    public function testSuccessfulCreating(string $payload): void
    {
        $url = Url::tryCreateFromString($payload);

        self::assertSame($payload, (string) $url);
    }

    /**
     * @dataProvider provideUnsuccessfulCreatingCases
     *
     * @psalm-param non-empty-string $payload
     */
    public function testUnsuccessfulCreating(string $payload): void
    {
        try {
            Url::tryCreateFromString($payload);
            self::fail('Expected exception to be thrown.');
        } catch (Exception $exception) {
            self::assertInstanceOf(UrlIsInvalidException::class, $exception);
        }
    }
}
