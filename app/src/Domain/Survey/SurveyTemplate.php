<?php

declare(strict_types=1);

namespace App\Domain\Survey;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class SurveyTemplate
{
    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $createdAt;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?DateTimeImmutable $deletedAt;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $name;

    #[ORM\OneToMany(mappedBy: 'template', targetEntity: Question::class)]
    public Collection $questions;

    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(string $name)
    {
        $this->id = Uuid::v4();
        $this->questions = new ArrayCollection();
        $this->deletedAt = null;

        $this->createdAt = new DateTimeImmutable();

        $this->name = $name;
    }
}
