<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Denormalizer;

use App\Infrastructure\Denormalizer\ConstraintViolation\UuidIsNotValid;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Tests\BaseWebTestCase;
use Spiks\UserInputProcessor\Exception\ValidationError;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 *
 * @covers \App\Infrastructure\Denormalizer\UuidDenormalizer
 */
final class UuidDenormalizerTest extends BaseWebTestCase
{
    /**
     * @psalm-return list<array<string>>
     */
    public static function provideSuccessfulDenormalizationCases(): iterable
    {
        return [
            ['ac7434f5-93bb-48df-bc25-dcf6962b0217'],
            ['973353ef-fb5b-4b5a-aee7-54f91d9add93'],
            ['dd18fda4-c503-41d8-84b0-b4e61ba36510'],
            ['dd6cb2a8-8b52-4f39-a27e-cac0b29355eb'],
            ['0f56124c-4804-4da2-9893-969de440d05c'],
        ];
    }

    /**
     * @psalm-return list<array<string>>
     */
    public static function provideUnsuccessfulDenormalizationCases(): iterable
    {
        return [
            ['ssssssss-aaaa-ffff-qqqq-b4e61ba36510'],
            ['123'],
            ['11111111-1111-1111-1111-111111111111'],
            ['11111111-1111-1111-111111111111'],
        ];
    }

    /**
     * @dataProvider provideSuccessfulDenormalizationCases
     */
    public function testSuccessfulDenormalization(string $payload): void
    {
        $uuid = $this->getService(UuidDenormalizer::class)->denormalize($payload, Pointer::empty());

        self::assertTrue($uuid->equals(Uuid::fromString($payload)));
    }

    /**
     * @dataProvider provideUnsuccessfulDenormalizationCases
     */
    public function testUnsuccessfulDenormalization(string $payload): void
    {
        try {
            $this->getService(UuidDenormalizer::class)->denormalize($payload, Pointer::empty());
        } catch (ValidationError $exception) {
            self::assertCount(1, $exception->getViolations());
            self::assertContainsOnly(UuidIsNotValid::class, $exception->getViolations());
        }
    }
}
