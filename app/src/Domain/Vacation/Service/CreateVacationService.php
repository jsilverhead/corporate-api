<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Service;

use App\Domain\Common\ValueObject\Period;
use App\Domain\Employee\Employee;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Vacation;

readonly class CreateVacationService
{
    public function __construct(private VacationRepository $vacationRepository)
    {
    }

    public function create(Employee $employee, Period $period): Vacation
    {
        $vacation = new Vacation(employee: $employee, fromDate: $period->fromDate, toDate: $period->toDate);
        $employee->vacations->add($vacation);

        $this->vacationRepository->add($vacation);

        return $vacation;
    }
}
