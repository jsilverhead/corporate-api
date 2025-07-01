<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Common\Enum\CustomTypes;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $createdAt;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?DateTimeImmutable $deletedAt;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: CustomTypes::EMAIL, length: 255, unique: true)]
    public Email $email;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $name;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::STRING, length: 255)]
    public ?string $password;

    /**
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::STRING, enumType: RolesEnum::class)]
    public RolesEnum $role;

    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(string $name, Email $email, RolesEnum $role)
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();

        $this->name = $name;
        $this->email = $email;
        $this->role = $role;

        $this->password = null;
        $this->deletedAt = null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getUserIdentifier(): string
    {
        return $this->id->toRfc4122();
    }
}
