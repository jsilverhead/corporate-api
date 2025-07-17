<?php

declare(strict_types=1);

namespace App\Domain\Survey;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Question
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $question;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\ManyToOne(targetEntity: SurveyTemplate::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: true)]
    public ?SurveyTemplate $template;

    public function __construct(string $question)
    {
        $this->id = Uuid::v4();

        $this->question = $question;
        $this->template = null;
    }
}
