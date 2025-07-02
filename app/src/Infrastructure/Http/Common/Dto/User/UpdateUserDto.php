<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\User;

use App\Domain\User\Enum\RolesEnum;
use Symfony\Component\Uid\Uuid;

readonly class UpdateUserDto
{
    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(public Uuid $userId, public string $name, public RolesEnum $role)
    {
    }
}
