<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\User;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Exception\UserAlreadyDeletedException;
use App\Domain\User\Service\DeleteUserService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteUserService::class)]
final class DeleteUserServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $email = Email::tryCreateFromString('olego@company.ru');

        $user = $this->getService(UserBuilder::class)
            ->withEmail($email)
            ->build();

        $this->getService(DeleteUserService::class)->delete($user);

        self::assertNotSame(expected: $email, actual: $user->email);
        self::assertNotNull($user->deletedAt);
    }

    public function testUserAlreadyDeletedFail(): void
    {
        $user = $this->getService(UserBuilder::class)
            ->asDeleted()
            ->build();

        $this->expectException(UserAlreadyDeletedException::class);
        $this->getService(DeleteUserService::class)->delete($user);
    }
}
