<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class AnotherVacationExistsInsideTheChosenPeriodException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Another vacation exists in the chosen period';
    }

    public function getType(): string
    {
        return 'another_vacation_exists_in_the_chosen_period';
    }
}
