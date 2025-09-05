<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Vacation;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Vacation\Exception\CanNotUpdateApprovedVacationException;
use App\Domain\Vacation\Exception\FromDateCanNotBeLessThatFourteenDaysFromNowException;
use App\Domain\Vacation\Exception\VacationCanNotBeInThePastException;
use App\Domain\Vacation\Service\UpdateVacationService;
use App\Tests\BaseWebTestCase;
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
    public function testFromDateInThePastFail(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();
        $fromDate = new DateTimeImmutable('-10 days');
        $toDate = new DateTimeImmutable('-5 days');
        $newPeriod = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->expectException(VacationCanNotBeInThePastException::class);
        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $newPeriod);
    }

    public function testFromDateLessThatFourteenDaysFail(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();
        $fromDate = new DateTimeImmutable('+10 days');
        $toDate = new DateTimeImmutable('+12 days');
        $newPeriod = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->expectException(FromDateCanNotBeLessThatFourteenDaysFromNowException::class);
        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $newPeriod);
    }

    public function testUpdateApprovedVacationFail(): void
    {
        $vacation = $this->getService(VacationBuilder::class)
            ->asApproved()
            ->build();
        $fromDate = new DateTimeImmutable('+30 days');
        $toDate = new DateTimeImmutable('+37 days');
        $newPeriod = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->expectException(CanNotUpdateApprovedVacationException::class);
        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $newPeriod);
    }

    public function testSuccess(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();
        $fromDate = new DateTimeImmutable('+30 days');
        $toDate = new DateTimeImmutable('+37 days');
        $newPeriod = new Period(fromDate: $fromDate, toDate: $toDate);

        $this->getService(UpdateVacationService::class)->update(vacation: $vacation, period: $newPeriod);

        self::assertSame(expected: $vacation->fromDate->getTimestamp(), actual: $fromDate->getTimestamp());
        self::assertSame(expected: $vacation->toDate->getTimestamp(), actual: $toDate->getTimestamp());
    }
}
