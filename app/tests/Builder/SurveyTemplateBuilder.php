<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Survey\Service\CreateSurveyTemplateService;
use App\Domain\Survey\SurveyTemplate;
use Doctrine\ORM\EntityManagerInterface;

class SurveyTemplateBuilder
{
    /** @psalm-var non-empty-string|null $name */
    private ?string $name = null;

    /** @psalm-var list<non-empty-string> $questions */
    private array $questions = [];

    public function __construct(
        private readonly CreateSurveyTemplateService $createSurveyTemplateService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function build(): SurveyTemplate
    {
        if (0 === \count($this->questions)) {
            $this->questions[] = 'Какие ваши самые значимые достижения на предыдущем месте работы?';
        }

        $surveyTemplate = $this->createSurveyTemplateService->create(
            questions: $this->questions,
            name: $this->name ?? 'Для руководителей',
        );

        $this->entityManager->flush();

        return $surveyTemplate;
    }

    /**
     * @psalm-param list<non-empty-string> $questions
     */
    public function withQuestions(array $questions): self
    {
        $this->questions = $questions;

        return $this;
    }
}
