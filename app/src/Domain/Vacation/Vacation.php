<?php

declare(strict_types=1);

namespace App\Domain\Vacation;

use App\Domain\Employee\Employee;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Vacation
{
    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $createdAt;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'vacations')]
    public Employee $employee;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $fromDate;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $isApproved;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $toDate;

    public function __construct(Employee $employee, DateTimeImmutable $fromDate, DateTimeImmutable $toDate)
    {
        $this->id = Uuid::v4();
        $this->isApproved = false;
        $this->createdAt = new DateTimeImmutable();

        $this->employee = $employee;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
}
