<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class FromDateCanNotBeLessThatFourteenDaysFromNowException extends ServiceException
{
    public function getDescription(): string
    {
        return 'From date cannot be less than fourteen days from now';
    }

    public function getType(): string
    {
        return 'from_date_cannot_be_less_that_fourteen_days_from_now';
    }
}
