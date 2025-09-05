<?php

declare(strict_types=1);

namespace App\Infrastructure\Denormalizer;

use App\Domain\Common\ValueObject\Period;
use DateTimeImmutable;
use Spiks\UserInputProcessor\Denormalizer\DateTimeDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

readonly class PeriodDenormalizer
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

        $startDate = $denormalizedPeriod['fromDate']->format('Y-m-d');
        $endDate = $denormalizedPeriod['toDate']->format('Y-m-d');
        $fromDate = new DateTimeImmutable(sprintf('%sT00:00:00', $startDate));
        $toDate = new DateTimeImmutable(sprintf('%sT23:59:59', $endDate));

        return new Period(fromDate: $fromDate, toDate: $toDate);
    }
}
