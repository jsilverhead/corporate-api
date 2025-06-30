<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Common\Enum\CustomTypes;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\Enum\RolesEnum;
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
    #[ORM\Column(type: CustomTypes::EMAIL, length: 255, unique: true)]
    public Email $email;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $name;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public ?string $password;

    #[ORM\Column(type: Types::STRING, enumType: RolesEnum::class)]
    public RolesEnum $role;

    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(string $name, Email $email, RolesEnum $role)
    {
        $this->id = Uuid::v4();

        $this->name = $name;
        $this->email = $email;
        $this->role = $role;

        $this->password = null;
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
