<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User;

use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Service\UpdateUserService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateUserService::class)]
final class UpdateUserServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $newName = 'Олег Назарович';
        $newRole = RolesEnum::SUPERUSER;

        $user = $this->getService(UserBuilder::class)->build();

        $this->getService(UpdateUserService::class)->update(user: $user, name: $newName, role: $newRole);

        self::assertSame(expected: $newName, actual: $user->name);
        self::assertSame(expected: $newRole, actual: $user->role);
    }
}
