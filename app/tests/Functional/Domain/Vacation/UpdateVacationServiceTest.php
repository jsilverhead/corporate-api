<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Vacation;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Vacation\Exception\AnotherVacationExistsInsideTheChosenPeriodException;
use App\Domain\Vacation\Exception\CanNotUpdateApprovedVacationException;
use App\Domain\Vacation\Exception\FromDateCanNotBeInThePastException;
use App\Domain\Vacation\Service\UpdateVacationService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\VacationBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateVacationService::class)]
final class UpdateVacationServiceTest extends BaseWebTestCase
{
    public function testApprovedVacationFail(): void
    {
        $vacation = $this->getService(VacationBuilder::class)
            ->asApproved()
            ->build();
        $now = new DateTimeImmutable();
        $fromDate = $now->modify('+2 days');
        $toDate = $fromDate->modify('+15 days');
        $period = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->expectException(CanNotUpdateApprovedVacationException::class);
        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $period);
    }

    public function testFromDateInThePastFail(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();
        $now = new DateTimeImmutable();
        $fromDate = $now->modify('-1 day');
        $toDate = $fromDate->modify('+7 days');
        $period = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->expectException(FromDateCanNotBeInThePastException::class);
        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $period);
    }

    public function testPeriodCrossesAnotherVacationPeriodFail(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $now = new DateTimeImmutable();
        $fromDate = $now->modify('+1 month');
        $toDate = $now->modify('+1 month 7 days');
        $previousVacationPeriod = new Period(fromDate: $fromDate, toDate: $toDate);

        $vacationBuilder = $this->getService(VacationBuilder::class)->withEmployee($employee);
        $vacationForUpdate = $vacationBuilder->build();
        $this->getService(VacationBuilder::class)
            ->withPeriod($previousVacationPeriod)
            ->build();

        $newPeriod = new Period(fromDate: $fromDate->modify('+2 days'), toDate: $toDate->modify('+14 days'));

        $this->expectException(AnotherVacationExistsInsideTheChosenPeriodException::class);
        $this->getService(UpdateVacationService::class)->update(vacation: $vacationForUpdate, period: $newPeriod);
    }

    public function testSuccess(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();
        $now = new DateTimeImmutable();
        $fromDate = $now->modify('+2 days');
        $toDate = $fromDate->modify('+15 days');
        $period = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $period);

        self::assertSame(expected: $fromDate->getTimestamp(), actual: $vacation->fromDate->getTimestamp());
        self::assertSame(expected: $toDate->getTimestamp(), actual: $vacation->toDate->getTimestamp());
    }
}
