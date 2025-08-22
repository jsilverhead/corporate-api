<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Vacation;

use App\Domain\Common\ValueObject\Pagination;
use App\Domain\Common\ValueObject\Period;
use App\Infrastructure\Denormalizer\PaginationDenormalizer;
use App\Infrastructure\Denormalizer\PeriodDenormalizer;
use App\Infrastructure\Denormalizer\UuidDenormalizer;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\Enum\VacationsStatusEnum;
use App\Infrastructure\Http\Common\Dto\Dto\ListVacationsDto;
use App\Infrastructure\Payload\Payload;
use Spiks\UserInputProcessor\Denormalizer\EnumerationDenormalizer;
use Spiks\UserInputProcessor\Denormalizer\ObjectDenormalizer;
use Spiks\UserInputProcessor\ObjectField;
use Spiks\UserInputProcessor\Pointer;
use Symfony\Component\Uid\Uuid;

class ListVacationsDenormalizer
{
    public function __construct(
        private ObjectDenormalizer $objectDenormalizer,
        private PeriodDenormalizer $periodDenormalizer,
        private UuidDenormalizer $uuidDenormalizer,
        private EnumerationDenormalizer $enumerationDenormalizer,
        private PaginationDenormalizer $paginationDenormalizer,
    ) {
    }

    public function denormalize(Payload $payload): ListVacationsDto
    {
        /**
         * @psalm-var array{
         *     pagination: Pagination,
         *     period: Period,
         *     employeeId: Uuid,
         *     departmentId: Uuid,
         *     status: VacationsStatusEnum
         * } $denormalizedData
         */
        $denormalizedData = $this->objectDenormalizer->denormalize(
            data: $payload->arguments,
            pointer: Pointer::empty(),
            fieldDenormalizers: [
                'pagination' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Pagination => $this->paginationDenormalizer->denormalize(
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
                'employeeId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                    isMandatory: false,
                ),
                'departmentId' => new ObjectField(
                    fn(mixed $data, Pointer $pointer): Uuid => $this->uuidDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                    ),
                    isMandatory: false,
                ),
                'status' => new ObjectField(
                    fn(
                        mixed $data,
                        Pointer $pointer,
                    ): VacationsStatusEnum => $this->enumerationDenormalizer->denormalize(
                        data: $data,
                        pointer: $pointer,
                        enumClassName: VacationsStatusEnum::class,
                    ),
                ),
            ],
        );

        return new ListVacationsDto(
            pagination: $denormalizedData['pagination'],
            period: $denormalizedData['period'],
            status: $denormalizedData['status'],
            employeeId: $denormalizedData['employeeId'] ?? null,
            departmentId: $denormalizedData['departmentId'] ?? null,
        );
    }
}
