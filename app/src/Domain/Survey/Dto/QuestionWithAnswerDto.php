<?php

declare(strict_types=1);

namespace App\Domain\Survey\Dto;

use App\Domain\Survey\Question;

readonly class QuestionWithAnswerDto
{
    /**
     * @psalm-param non-empty-string $answer
     */
    public function __construct(public Question $question, public string $answer)
    {
    }
}
