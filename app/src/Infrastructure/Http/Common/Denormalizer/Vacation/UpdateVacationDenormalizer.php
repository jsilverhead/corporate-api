<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Vacation;

use App\Domain\Common\ValueObject\Period;
use App\Infrastructure\Denormalizer\PeriodDenormalizer;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Dto\Vacation\UpdateVacationDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

readonly class UpdateVacationDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
        private PeriodDenormalizer $periodDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): UpdateVacationDto
    {
        /**
         * @psalm-var array{
         *     vacationId: Uuid,
         *     period: Period
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'vacationId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
                'period' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Period => $this->periodDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                ),
            ],
        );

        return new UpdateVacationDto(vacationId: $denormalizedData['vacationId'], period: $denormalizedData['period']);
    }
}
