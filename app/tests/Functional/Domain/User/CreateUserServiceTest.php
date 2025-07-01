<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Exception\UserWithThisEmailAlreadyExistsException;
use App\Domain\User\Service\CreateUserService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;

/**
 * @internal
 *
 * @coversNothing
 */
final class CreateUserServiceTest extends BaseWebTestCase
{
    public function testEmailAlreadyExistsFail(): void
    {
        $email = Email::tryCreateFromString('olego@company.ru');
        $this->getService(UserBuilder::class)
            ->withEmail($email)
            ->build();

        $this->expectException(UserWithThisEmailAlreadyExistsException::class);
        $this->getService(CreateUserService::class)->create(
            name: 'Олегов Олег',
            email: $email,
            password: 'Password123',
            role: RolesEnum::USER,
        );
    }

    public function testSuccess(): void
    {
        $name = 'Олегов Олег';
        $email = Email::tryCreateFromString('olego@company.ru');
        $password = 'Password123';
        $role = RolesEnum::USER;

        $user = $this->getService(CreateUserService::class)->create(
            name: $name,
            email: $email,
            password: $password,
            role: $role,
        );

        self::assertSame(expected: $name, actual: $user->name);
        self::assertSame($email, $user->email);
        self::assertSame($role, $user->role);
    }
}
