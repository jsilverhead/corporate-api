<?php

declare(strict_types=1);

namespace App\Domain\User\Enum;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
