<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Common\ValueObject\Period;
use DateTimeImmutable;
use Spiks\UserInputProcessor\Denormalizer\DateTimeDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class PeriodDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private DateTimeDenormalizer $dateTimeDenormalizer,
    ) {
    }

    public function denormalize(mixed $data, Pointer $pointer): Period
    {
        /**
         * @psalm-var array{
         *     fromDate: \DateTimeImmutable,
         *     toDate: \DateTimeImmutable
         * } $denormalizedPeriod
         */
        $denormalizedPeriod = $this->objectDenormalizer->denormalize(
            data: $data,
            pointer: $pointer,
            fieldDenormalizers: [
                'fromDate' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): DateTimeImmutable => $this->dateTimeDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'toDate' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): DateTimeImmutable => $this->dateTimeDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new Period(fromDate: $denormalizedPeriod['fromDate'], toDate: $denormalizedPeriod['toDate']);
    }
}
