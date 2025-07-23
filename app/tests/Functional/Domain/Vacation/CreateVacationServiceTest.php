<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Vacation;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Vacation\Service\CreateVacationService;
use App\Domain\Vacation\Vacation;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateVacationService::class)]
final class CreateVacationServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $now = new DateTimeImmutable();
        $fromDate = $now->modify('+1 day');
        $toDate = $fromDate->modify('+8 days');
        $period = new Period(fromDate: $fromDate, toDate: $toDate);

        $vacation = $this->getService(CreateVacationService::class)->create(employee: $employee, period: $period);

        self::assertInstanceOf(expected: Vacation::class, actual: $vacation);
        self::assertSame(expected: $fromDate->getTimestamp(), actual: $vacation->fromDate->getTimestamp());
        self::assertSame(expected: $toDate->getTimestamp(), actual: $vacation->toDate->getTimestamp());
        self::assertTrue($employee->id->equals($vacation->employee->id));
    }
}
