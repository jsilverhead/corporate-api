<?php

declare(strict_types=1);

namespace App\Infrastructure\Attribute;

use App\Domain\User\Enum\RolesEnum;
use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
readonly class AllowedUserRole
{
    /**
     * @psalm-param non-empty-list<RolesEnum> $roles
     */
    public function __construct(public array $roles)
    {
    }
}
