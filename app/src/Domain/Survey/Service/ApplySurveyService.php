<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Survey\Dto\AnswerDataDto;
use App\Domain\Survey\Exception\AnswersCountIsNotEqualQuestionsCountException;
use App\Domain\Survey\Exception\SurveyIsAlreadyCompletedException;
use App\Domain\Survey\Survey;

readonly class ApplySurveyService
{
    public function __construct(private CreateSurveyAnswerService $createSurveyAnswerService)
    {
    }

    /**
     * @psalm-param list<AnswerDataDto> $answerData
     */
    public function apply(Survey $survey, array $answerData): void
    {
        if ($survey->isCompleted) {
            throw new SurveyIsAlreadyCompletedException();
        }

        if (\count($answerData) !== $survey->template->questions->count()) {
            throw new AnswersCountIsNotEqualQuestionsCountException();
        }

        foreach ($answerData as $data) {
            $this->createSurveyAnswerService->create(survey: $survey, question: $data->question, answer: $data->answer);
        }

        $survey->isCompleted = true;
    }
}
