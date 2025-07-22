<?php

declare(strict_types=1);

namespace App\Domain\Survey;

use App\Domain\Employee\Employee;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Survey
{
    #[ORM\OneToMany(mappedBy: 'survey', targetEntity: SurveyAnswer::class)]
    public Collection $answers;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public DateTimeImmutable $createdAt;

    #[ORM\OneToOne(targetEntity: Employee::class)]
    public Employee $employee;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $isCompleted;

    #[ORM\ManyToOne(targetEntity: SurveyTemplate::class)]
    public SurveyTemplate $template;

    public function __construct(SurveyTemplate $template, Employee $employee)
    {
        $this->id = Uuid::v4();
        $this->isCompleted = false;
        $this->answers = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();

        $this->employee = $employee;
        $this->template = $template;
    }
}
