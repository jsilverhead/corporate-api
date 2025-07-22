<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Common\Exception\EntityNotFound\EntitiesNotFoundByIdsException;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Survey\Dto\AnswerDataDto;
use App\Domain\Survey\Dto\QuestionWithAnswerDto;
use App\Domain\Survey\Question;
use App\Domain\Survey\Repository\QuestionRepository;
use App\Domain\Survey\Survey;
use Symfony\Component\Uid\Uuid;

readonly class QuestionWithAnswerValidatorAndCreator
{
    public function __construct(private QuestionRepository $questionRepository)
    {
    }

    /**
     * @psalm-param list<AnswerDataDto> $answerData
     *
     * @psalm-return list<QuestionWithAnswerDto>
     */
    public function create(array $answerData, Survey $survey): array
    {
        $questions = $this->validateAndReturnQuestions(answerData: $answerData, survey: $survey);

        /**
         * @psalm-var list<QuestionWithAnswerDto> $questionsWithAnswers
         */
        $questionsWithAnswers = [];

        foreach ($answerData as $data) {
            foreach ($questions as $question) {
                if ($data->questionId->equals($question->id)) {
                    $questionsWithAnswers[] = new QuestionWithAnswerDto(question: $question, answer: $data->answer);
                }
            }
        }

        return $questionsWithAnswers;
    }

    /**
     * @psalm-param list<AnswerDataDto> $answerData
     *
     * @psalm-return list<Question>
     */
    private function validateAndReturnQuestions(array $answerData, Survey $survey): array
    {
        $questionsIds = [];

        foreach ($answerData as $data) {
            $questionsIds[] = $data->questionId;
        }

        $questions = $this->questionRepository->getByIdsAndSurvey(survey: $survey, questionIds: $questionsIds);

        $foundIds = array_map(static fn(Question $question): Uuid => $question->id, $questions);
        $notFoundIds = array_diff($questionsIds, $foundIds);

        if (\count($questions) < \count($answerData)) {
            throw new EntitiesNotFoundByIdsException(entity: EntityNotFoundEnum::QUESTION, ids: $notFoundIds);
        }

        return $questions;
    }
}
