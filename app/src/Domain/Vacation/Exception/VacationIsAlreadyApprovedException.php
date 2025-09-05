<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class VacationIsAlreadyApprovedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Vacation is already approved';
    }

    public function getType(): string
    {
        return 'vacation_is_already_approved';
    }
}
