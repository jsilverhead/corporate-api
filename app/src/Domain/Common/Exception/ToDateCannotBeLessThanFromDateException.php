<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception;

class ToDateCannotBeLessThanFromDateException extends ServiceException
{
    public function getDescription(): string
    {
        return 'From date cannot be less than to_date';
    }

    public function getType(): string
    {
        return 'from_date_cannot_be_less_than_to_date';
    }
}
