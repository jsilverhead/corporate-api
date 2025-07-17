<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Survey\Exception\QuestionAlreadyHaveAnswerException;
use App\Domain\Survey\Question;
use App\Domain\Survey\Repository\SurveyAnswerRepository;
use App\Domain\Survey\Survey;
use App\Domain\Survey\SurveyAnswer;

readonly class CreateSurveyAnswerService
{
    public function __construct(private SurveyAnswerRepository $surveyAnswerRepository)
    {
    }

    public function create(Survey $survey, Question $question, string $answer): SurveyAnswer
    {
        $isQuestionHaveAnswer = $this->surveyAnswerRepository->isQuestionAlreadyHaveAnswer(
            survey: $survey,
            question: $question,
        );

        if ($isQuestionHaveAnswer) {
            throw new QuestionAlreadyHaveAnswerException();
        }

        $answer = new SurveyAnswer(survey: $survey, question: $question, answer: $answer);

        $survey->answers->add($answer);
        $this->surveyAnswerRepository->add($answer);

        return $answer;
    }
}
