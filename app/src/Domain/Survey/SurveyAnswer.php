<?php

declare(strict_types=1);

namespace App\Domain\Survey;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class SurveyAnswer
{
    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Column(type: Types::TEXT)]
    public string $answer;

    /** @psalm-suppress PossiblyUnusedProperty */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    public Question $question;

    #[ORM\ManyToOne(targetEntity: Survey::class, inversedBy: 'answers')]
    public Survey $survey;

    public function __construct(Survey $survey, Question $question, string $answer)
    {
        $this->id = Uuid::v4();

        $this->survey = $survey;
        $this->question = $question;
        $this->answer = $answer;
    }
}
