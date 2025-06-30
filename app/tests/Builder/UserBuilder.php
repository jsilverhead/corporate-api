<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Service\CreateUserService;
use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;

class UserBuilder
{
    private ?Email $email = null;

    public function __construct(
        private CreateUserService $createUserService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function build(): User
    {
        $name = 'Олег Олегович';
        $password = 'Spiks123';

        $user = $this->createUserService->create(
            name: $name,
            email: $this->email ?? Email::tryCreateFromString(uniqid(more_entropy: true) . '@spiks.ru'),
            password: $password,
            role: RolesEnum::USER,
        );

        $this->entityManager->flush();

        return $user;
    }

    public function withEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }
}
