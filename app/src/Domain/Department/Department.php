<?php

declare(strict_types=1);

namespace App\Domain\Department;

use App\Domain\User\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Department
{
    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?DateTimeImmutable $deletedAt;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\OneToMany(mappedBy: 'department', targetEntity: User::class)]
    public Collection $employees;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    public string $name;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\OneToMany(mappedBy: 'supervising', targetEntity: User::class)]
    public Collection $supervisors;

    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(string $name)
    {
        $this->id = Uuid::v4();
        $this->employees = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
        $this->deletedAt = null;

        $this->name = $name;
    }
}
