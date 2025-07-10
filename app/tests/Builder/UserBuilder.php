<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Department\Department;
use App\Domain\User\Enum\RolesEnum;
use App\Domain\User\Service\CreateUserService;
use App\Domain\User\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class UserBuilder
{
    private ?DateTimeImmutable $birthDate = null;

    private ?Email $email = null;

    private bool $isDeleted = false;

    /** @psalm-var non-empty-string|null $name */
    private ?string $name = null;

    /** @psalm-var non-empty-string|null $password */
    private ?string $password = null;

    private RolesEnum $role = RolesEnum::USER;

    private ?Department $supervising = null;

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
        $user = $this->createUserService->create(
            name: $this->name ?? 'Олег Олегович',
            email: $this->email ?? Email::tryCreateFromString(uniqid(more_entropy: true) . '@company.ru'),
            password: $this->password ?? 'Password123',
            role: $this->role,
            birthDate: $this->birthDate,
        );

        if ($this->isDeleted) {
            $user->deletedAt = new DateTimeImmutable();
        }

        if (null !== $this->supervising) {
            $user->supervising = $this->supervising;
            $this->supervising->supervisors->add($user);
        }

        $this->entityManager->flush();

        return $user;
    }

    public function withBirthDate(DateTimeImmutable $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function withEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @psalm-param non-empty-string $password
     */
    public function withPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function withSupervising(Department $department): self
    {
        $this->supervising = $department;

        return $this;
    }
}
