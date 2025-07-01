<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Exception\UserAlreadyDeletedException;
use App\Domain\User\User;
use DateTimeImmutable;

class DeleteUserService
{
    public function __construct()
    {
    }

    public function delete(User $user): void
    {
        if (null !== $user->deletedAt) {
            throw new UserAlreadyDeletedException();
        }

        $user->deletedAt = new DateTimeImmutable();
        $user->email = Email::tryCreateFromString(uniqid(prefix: 'deleted_', more_entropy: true) . '@spiks.ru');
    }
}
