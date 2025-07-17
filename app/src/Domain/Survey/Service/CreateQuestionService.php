<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Survey\Question;
use App\Domain\Survey\Repository\QuestionRepository;

readonly class CreateQuestionService
{
    public function __construct(private QuestionRepository $questionRepository)
    {
    }

    /**
     * @psalm-param non-empty-string $question
     */
    public function create(string $question): Question
    {
        $question = new Question($question);

        $this->questionRepository->add($question);

        return $question;
    }
}
