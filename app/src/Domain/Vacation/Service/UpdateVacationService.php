<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Service;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Vacation\Exception\CanNotUpdateApprovedVacationException;
use App\Domain\Vacation\Exception\FromDateCanNotBeLessThatFourteenDaysFromNowException;
use App\Domain\Vacation\Exception\VacationCanNotBeInThePastException;
use App\Domain\Vacation\Vacation;
use DateInterval;
use DateTimeImmutable;

class UpdateVacationService
{
    public function update(Vacation $vacation, Period $period): void
    {
        if ($vacation->isApproved) {
            throw new CanNotUpdateApprovedVacationException();
        }

        $now = new DateTimeImmutable();
        /** @psalm-var DateInterval $allowedInterval */
        $allowedInterval = DateInterval::createFromDateString('14 days');

        if ($period->fromDate < $now) {
            throw new VacationCanNotBeInThePastException();
        }

        if ($period->fromDate->sub($allowedInterval) < $now) {
            throw new FromDateCanNotBeLessThatFourteenDaysFromNowException();
        }

        $vacation->fromDate = $period->fromDate;
        $vacation->toDate = $period->toDate;
    }
}
