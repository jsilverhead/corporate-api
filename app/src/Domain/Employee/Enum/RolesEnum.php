<?php

declare(strict_types=1);

namespace App\Domain\Employee\Enum;

enum RolesEnum: string
{
    case SUPERUSER = 'superuser';
    case USER = 'user';
}
