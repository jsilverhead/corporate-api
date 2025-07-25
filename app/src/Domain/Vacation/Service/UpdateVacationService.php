<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Service;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Vacation\Exception\AnotherVacationExistsInsideTheChosenPeriodException;
use App\Domain\Vacation\Exception\CanNotUpdateApprovedVacationException;
use App\Domain\Vacation\Exception\FromDateCanNotBeInThePastException;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Vacation;
use DateTimeImmutable;

class UpdateVacationService
{
    public function __construct(private VacationRepository $vacationRepository)
    {
    }

    public function update(Vacation $vacation, Period $period): void
    {
        if ($vacation->isApproved) {
            throw new CanNotUpdateApprovedVacationException();
        }

        $now = new DateTimeImmutable();

        if ($period->fromDate < $now) {
            throw new FromDateCanNotBeInThePastException();
        }

        $vacationWithCrossedPeriod = $this->vacationRepository->getVacationWithPeriodCrossingCurrentPeriod(
            employee: $vacation->employee,
            period: $period,
        );

        if (null !== $vacationWithCrossedPeriod && !$vacationWithCrossedPeriod->id->equals($vacation->id)) {
            throw new AnotherVacationExistsInsideTheChosenPeriodException();
        }

        $vacation->fromDate = $period->fromDate;
        $vacation->toDate = $period->toDate;
    }
}
