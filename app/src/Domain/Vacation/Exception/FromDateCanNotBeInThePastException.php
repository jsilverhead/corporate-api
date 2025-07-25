<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class FromDateCanNotBeInThePastException extends ServiceException
{
    public function getDescription(): string
    {
        return 'From date cannot be in the past.';
    }

    public function getType(): string
    {
        return 'from_date_cannot_be_in_the_past';
    }
}
