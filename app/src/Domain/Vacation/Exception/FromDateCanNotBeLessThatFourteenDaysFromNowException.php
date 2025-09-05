<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class FromDateCanNotBeLessThatFourteenDaysFromNowException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Vacation cannot be in the past';
    }

    public function getType(): string
    {
        return 'vacation_can_not_be_in_the_past';
    }
}
