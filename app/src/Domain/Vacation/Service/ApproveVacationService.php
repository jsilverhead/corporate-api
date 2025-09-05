<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Service;

use App\Domain\Vacation\Exception\VacationIsAlreadyApprovedException;
use App\Domain\Vacation\Vacation;

class ApproveVacationService
{
    public function approve(Vacation $vacation): void
    {
        if ($vacation->isApproved) {
            throw new VacationIsAlreadyApprovedException();
        }

        $vacation->isApproved = true;
    }
}
