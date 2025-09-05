<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Service;

use App\Domain\Vacation\Exception\CannotDeleteSpentVacationException;
use App\Domain\Vacation\Repository\VacationRepository;
use App\Domain\Vacation\Vacation;
use DateTimeImmutable;

readonly class DeleteVacationService
{
    public function __construct(private VacationRepository $vacationRepository)
    {
    }

    public function delete(Vacation $vacation): void
    {
        $now = new DateTimeImmutable();

        if ($vacation->toDate < $now && $vacation->isApproved) {
            throw new CannotDeleteSpentVacationException();
        }

        $this->vacationRepository->remove($vacation);
    }
}
