<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Denormalizer;

use App\Infrastructure\Denormalizer\ConstraintViolation\EmailIsNotValid;
use App\Infrastructure\Denormalizer\EmailDenormalizer;
use App\Tests\BaseWebTestCase;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;

/**
 * @internal
 *
 * @covers \App\Infrastructure\Denormalizer\EmailDenormalizer
 */
final class EmailDenormalizerTest extends BaseWebTestCase
{
    /**
     * @psalm-return list<array<string>>
     */
    public static function provideSuccessfulDenormalizationCases(): iterable
    {
        return [['test@gmail.com'], ['test-asd@google.com'], ['Foo.Bar@foo.bar.google.com'], ['моя_почта@почта.рф']];
    }

    /**
     * @psalm-return list<array<string>>
     */
    public static function provideUnsuccessfulDenormalizationCases(): iterable
    {
        return [
            ['🤔🧐@gmail.com'],
            ['_00)_!"№%:,.;()_@gmail.com'],
            ['foo@foo'],
            ['foo@bar.🤔🧐.com'],
            ['foo@' . mb_str_pad('', 64, 'bar') . '.com'],
        ];
    }

    /**
     * @dataProvider provideSuccessfulDenormalizationCases
     */
    public function testSuccessfulDenormalization(string $payload): void
    {
        $processedData = $this->getService(EmailDenormalizer::class)->denormalize(
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
            $this->getService(EmailDenormalizer::class)->denormalize($payload, Pointer::empty());
        } catch (ValidationError $exception) {
            self::assertCount(expectedCount: 1, haystack: $exception->getViolations());
            self::assertContainsOnly(type: EmailIsNotValid::class, haystack: $exception->getViolations());
        }
    }
}
