<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Survey\Dto\QuestionWithAnswerDto;
use App\Domain\Survey\Exception\AnswersCountIsNotEqualQuestionsCountException;
use App\Domain\Survey\Survey;

readonly class ApplySurveyService
{
    public function __construct(private CreateSurveyAnswerService $createSurveyAnswerService)
    {
    }

    /**
     * @psalm-param list<QuestionWithAnswerDto> $questionsWithAnswer
     */
    public function apply(Survey $survey, array $questionsWithAnswer): void
    {
        if (\count($questionsWithAnswer) !== $survey->template->questions->count()) {
            throw new AnswersCountIsNotEqualQuestionsCountException();
        }

        foreach ($questionsWithAnswer as $data) {
            $this->createSurveyAnswerService->create(survey: $survey, question: $data->question, answer: $data->answer);
        }

        $survey->isCompleted = true;
    }
}
