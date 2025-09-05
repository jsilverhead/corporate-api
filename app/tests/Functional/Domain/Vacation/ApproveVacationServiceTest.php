<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Vacation;

use App\Domain\Vacation\Exception\VacationIsAlreadyApprovedException;
use App\Domain\Vacation\Service\ApproveVacationService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\VacationBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ApproveVacationService::class)]
final class ApproveVacationServiceTest extends BaseWebTestCase
{
    public function testAlreadyApprovedFail(): void
    {
        $vacation = $this->getService(VacationBuilder::class)
            ->asApproved()
            ->build();

        $this->expectException(VacationIsAlreadyApprovedException::class);
        $this->getService(ApproveVacationService::class)->approve($vacation);
    }

    public function testSuccess(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();

        $this->getService(ApproveVacationService::class)->approve($vacation);

        self::assertTrue($vacation->isApproved);
    }
}
