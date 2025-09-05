<?php

declare(strict_types=1);

namespace App\Domain\News;

use App\Domain\Employee\Employee;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class News
{
    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\ManyToOne(targetEntity: Employee::class)]
    public Employee $author;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::TEXT)]
    public string $content;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $createdAt;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $published;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $title;

    /**
     * @psalm-param non-empty-string $title
     * @psalm-param non-empty-string $content
     */
    public function __construct(Employee $author, string $title, string $content)
    {
        $this->id = Uuid::v4();
        $this->createdAt = new DateTimeImmutable();

        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->published = false;
    }
}
