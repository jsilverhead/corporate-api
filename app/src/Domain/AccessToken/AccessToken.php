<?php

declare(strict_types=1);

namespace App\Domain\AccessToken;

use App\Domain\Employee\Employee;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class AccessToken
{
    /**
     * @psalm-var non-empty-string $accessToken
     *
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::TEXT)]
    public string $accessToken;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $accessTokenExpiresAt;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $createdAt;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\ManyToOne(targetEntity: Employee::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    public Employee $employee;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $refreshExpiresAt;

    /**
     * @psalm-var non-empty-string $refreshToken
     *
     * @psalm-suppress PossiblyUnusedProperty
     */
    #[ORM\Column(type: Types::TEXT)]
    public string $refreshToken;

    /**
     * @psalm-param non-empty-string $accessToken
     * @psalm-param non-empty-string $refreshToken
     */
    public function __construct(
        Employee $employee,
        string $accessToken,
        string $refreshToken,
        DateTimeImmutable $accessTokenExpiresAt,
        DateTimeImmutable $refreshExpiresAt,
    ) {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();

        $this->employee = $employee;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->accessTokenExpiresAt = $accessTokenExpiresAt;
        $this->refreshExpiresAt = $refreshExpiresAt;
    }
}
