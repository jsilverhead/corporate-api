<?php

declare(strict_types=1);

namespace App\Domain\Vacation\Exception;

use App\Domain\Common\Exception\ServiceException;

class CanNotUpdateApprovedVacationException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Cannot update approved vacation';
    }

    public function getType(): string
    {
        return 'can_not_update_approved_vacation';
    }
}
