<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Vacation;

use App\Domain\Common\ValueObject\Period;
use App\Infrastructure\Denormalizer\PeriodDenormalizer;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;

class CreateVacationDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private PeriodDenormalizer $periodDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): Period
    {
        /**
         * @psalm-var array{
         *     period: Period
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'period' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Period => $this->periodDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return $denormalizedData['period'];
    }
}
