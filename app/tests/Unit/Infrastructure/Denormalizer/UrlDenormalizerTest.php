<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Denormalizer;

use App\Infrastructure\Denormalizer\ConstraintViolation\UrlIsNotValid;
use App\Infrastructure\Denormalizer\UrlDenormalizer;
use App\Tests\BaseWebTestCase;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

/**
 * @internal
 *
 * @covers \App\Infrastructure\Denormalizer\UrlDenormalizer
 */
final class UrlDenormalizerTest extends BaseWebTestCase
{
    /**
     * @psalm-return list<array<string>>
     */
    public static function provideSuccessfulDenormalizationCases(): iterable
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
     * @psalm-return list<array<string>>
     */
    public static function provideUnsuccessfulDenormalizationCases(): iterable
    {
        return [['www.google.com'], ['www.google.com/search?q=bar'], ['google.com'], ['googlecom']];
    }

    /**
     * @dataProvider provideSuccessfulDenormalizationCases
     */
    public function testSuccessfulDenormalization(string $payload): void
    {
        $processedData = $this->getService(UrlDenormalizer::class)->denormalize(
            data: $payload,
            pointer: Pointer::empty(),
        );

        self::assertSame($payload, (string) $processedData);
    }

    /**
     * @dataProvider provideUnsuccessfulDenormalizationCases
     */
    public function testUnsuccessfulDenormalization(string $payload): void
    {
        try {
            $this->getService(UrlDenormalizer::class)->denormalize($payload, Pointer::empty());
        } catch (ValidationError $exception) {
            self::assertCount(expectedCount: 1, haystack: $exception->getViolations());
            self::assertContainsOnly(type: UrlIsNotValid::class, haystack: $exception->getViolations());
        }
    }
}
