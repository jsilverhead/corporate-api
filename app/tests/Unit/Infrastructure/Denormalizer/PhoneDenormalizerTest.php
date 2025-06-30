<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Denormalizer;

use App\Infrastructure\Denormalizer\ConstraintViolation\PhoneIsNotValid;
use App\Infrastructure\Denormalizer\PhoneDenormalizer;
use App\Tests\BaseWebTestCase;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

/**
 * @internal
 *
 * @covers \App\Infrastructure\Denormalizer\PhoneDenormalizer
 */
final class PhoneDenormalizerTest extends BaseWebTestCase
{
    /**
     * @psalm-return list<array<string>>
     */
    public static function provideSuccessfulDenormalizationCases(): iterable
    {
        return [['+711122334444'], ['+77711122334444'], ['+12233344455']];
    }

    /**
     * @psalm-return list<array<string>>
     */
    public static function provideUnsuccessfulDenormalizationCases(): iterable
    {
        return [['+7O11122334444'], ['+777'], ['+122333444455555666666']];
    }

    /**
     * @dataProvider provideSuccessfulDenormalizationCases
     */
    public function testSuccessfulDenormalization(string $payload): void
    {
        $processedData = $this->getService(PhoneDenormalizer::class)->denormalize(
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
            $this->getService(PhoneDenormalizer::class)->denormalize($payload, Pointer::empty());
        } catch (ValidationError $exception) {
            self::assertCount(expectedCount: 1, haystack: $exception->getViolations());
            self::assertContainsOnly(type: PhoneIsNotValid::class, haystack: $exception->getViolations());
        }
    }
}
