<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Denormalizer\Vacation\Enum;

enum VacationsStatusEnum: string
{
    case ALL = 'all';
    case APPROVED = 'approved';
    case UNAPPROVED = 'unapproved';
}
