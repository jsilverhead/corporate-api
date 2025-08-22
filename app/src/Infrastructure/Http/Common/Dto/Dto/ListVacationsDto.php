<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Dto;

use App\Domain\Common\ValueObject\Pagination;
use App\Domain\Common\ValueObject\Period;
use App\Infrastructure\Http\Common\Denormalizer\Vacation\Enum\VacationsStatusEnum;
use Symfony\Component\Uid\Uuid;

readonly class ListVacationsDto
{
    public function __construct(
        public Pagination $pagination,
        public Period $period,
        public VacationsStatusEnum $status,
        public ?Uuid $employeeId,
        public ?Uuid $departmentId,
    ) {
    }
}
