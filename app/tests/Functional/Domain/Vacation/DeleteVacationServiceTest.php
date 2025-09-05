<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Vacation;

use App\Domain\Vacation\Exception\CannotDeleteSpentVacationException;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Service\DeleteVacationService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\VacationBuilder;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use SlopeIt\ClockMock\ClockMock;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteVacationService::class)]
final class DeleteVacationServiceTest extends BaseWebTestCase
{
    public function testSpentVacationFail(): void
    {
        $monthAgo = new DateTimeImmutable('-1 month');
        ClockMock::freeze($monthAgo);
        $vacation = $this->getService(VacationBuilder::class)
            ->asApproved()
            ->build();
        ClockMock::reset();

        $this->expectException(CannotDeleteSpentVacationException::class);
        $this->getService(DeleteVacationService::class)->delete($vacation);
    }

    public function testSuccess(): void
    {
        $vacation = $this->getService(VacationBuilder::class)->build();

        $this->getService(DeleteVacationService::class)->delete($vacation);
        $this->getService(EntityManagerInterface::class)->flush();

        $deletedVacation = $this->getService(VacationRepository::class)->getById($vacation->id);

        self::assertNull($deletedVacation);
    }
}
