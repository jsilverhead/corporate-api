<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Survey\Question;
use App\Domain\Survey\Service\CreateSurveyAnswerService;
use App\Domain\Survey\Survey;
use App\Domain\Survey\SurveyAnswer;
use Doctrine\ORM\EntityManagerInterface;

class SurveyAnswerBuilder
{
    private ?string $answer = null;

    private ?Question $question = null;

    /** @psalm-var list<non-empty-string> $questions */
    private array $questions = [];

    private ?Survey $survey = null;

    public function __construct(
        private readonly CreateSurveyAnswerService $createSurveyAnswerService,
        private readonly SurveyBuilder $surveyBuilder,
        private readonly QuestionBuilder $questionBuilder,
        private readonly SurveyTemplateBuilder $surveyTemplateBuilder,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function build(): SurveyAnswer
    {
        $questions = \count($this->questions) > 0 ? $this->questions : ['Расскажите о ваших хобби?'];

        $answer = $this->createSurveyAnswerService->create(
            survey: $this->survey ??
                $this->surveyBuilder->build(
                    surveyTemplate: $this->surveyTemplateBuilder->withQuestions($questions)->build(),
                ),
            question: $this->question ?? $this->questionBuilder->build(),
            answer: $this->answer ?? 'Хоббихорсинг',
        );

        $this->entityManager->flush();

        return $answer;
    }

    public function withQuestion(Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function withSurvey(Survey $survey): self
    {
        $this->survey = $survey;

        return $this;
    }
}
