<?php

declare(strict_types=1);

namespace App\Domain\Survey;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class SurveyTemplate
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\OneToMany(mappedBy: 'template', targetEntity: Question::class)]
    public Collection $questions;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->questions = new ArrayCollection();
    }
}
