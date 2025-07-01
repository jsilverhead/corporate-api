<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Service\CreateUserService;
use App\Domain\User\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class UserBuilder
{
    private ?Email $email = null;

    private bool $isDeleted = false;

    private RolesEnum $role = RolesEnum::USER;

    public function __construct(
        private CreateUserService $createUserService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function asDeleted(): self
    {
        $this->isDeleted = true;

        return $this;
    }

    public function asSuperUser(): self
    {
        $this->role = RolesEnum::SUPERUSER;

        return $this;
    }

    public function build(): User
    {
        $name = 'Олег Олегович';
        $password = 'Spiks123';

        $user = $this->createUserService->create(
            name: $name,
            email: $this->email ?? Email::tryCreateFromString(uniqid(more_entropy: true) . '@spiks.ru'),
            password: $password,
            role: $this->role,
        );

        if ($this->isDeleted) {
            $user->deletedAt = new DateTimeImmutable();
        }

        $this->entityManager->flush();

        return $user;
    }

    public function withEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }
}
