<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\User;

class UpdateUserService
{
    /**
     * @psalm-param non-empty-string $name
     */
    public function update(User $user, string $name, RolesEnum $role): void
    {
        $user->name = $name;
        $user->role = $role;
    }
}
