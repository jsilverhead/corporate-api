<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\Vacation;

use App\Domain\Common\ValueObject\Period;
use Symfony\Component\Uid\Uuid;

readonly class UpdateVacationDto
{
    public function __construct(public Uuid $vacationId, public Period $period)
    {
    }
}
