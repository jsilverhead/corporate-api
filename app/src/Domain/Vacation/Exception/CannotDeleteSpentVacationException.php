<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class CannotDeleteSpentVacationException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Cannot delete spent vacation';
    }

    public function getType(): string
    {
        return 'can_not_delete_spent_vacation';
    }
}
