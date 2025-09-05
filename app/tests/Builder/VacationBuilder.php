<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Employee\Employee;
use App\Domain\Vacation\Service\CreateVacationService;
use App\Domain\Vacation\Vacation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class VacationBuilder
{
    private bool $asApproved = false;

    private ?Employee $employee = null;

    private ?Period $period = null;

    public function __construct(
        private CreateVacationService $createVacationService,
        private EmployeeBuilder $employeeBuilder,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function asApproved(): self
    {
        $this->asApproved = true;

        return $this;
    }

    public function build(): Vacation
    {
        $vacation = $this->createVacationService->create(
            employee: $this->employee ?? $this->employeeBuilder->build(),
            period: $this->period ??
                new Period(fromDate: new DateTimeImmutable('+1 day'), toDate: new DateTimeImmutable('+8 days')),
        );

        if ($this->asApproved) {
            $vacation->isApproved = true;
        }

        $this->entityManager->flush();

        return $vacation;
    }

    public function withEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function withPeriod(Period $period): self
    {
        $this->period = $period;

        return $this;
    }
}
