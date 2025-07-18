<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Survey\Repository\SurveyTemplateRepository;
use App\Domain\Survey\SurveyTemplate;

readonly class CreateSurveyTemplateService
{
    public function __construct(
        private CreateQuestionService $createQuestionService,
        private SurveyTemplateRepository $templateRepository,
    ) {
    }

    /**
     * @psalm-param list<non-empty-string> $questions
     * @psalm-param non-empty-string $name
     */
    public function create(array $questions, string $name): SurveyTemplate
    {
        $surveyTemplate = new SurveyTemplate($name);

        foreach ($questions as $question) {
            $templateQuestion = $this->createQuestionService->create(question: $question);

            $templateQuestion->template = $surveyTemplate;
            $surveyTemplate->questions->add($templateQuestion);
        }

        $this->templateRepository->add($surveyTemplate);

        return $surveyTemplate;
    }
}
