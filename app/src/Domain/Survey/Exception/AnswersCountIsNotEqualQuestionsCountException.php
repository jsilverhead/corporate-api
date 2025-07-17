<?php

declare(strict_types=1);

namespace App\Domain\Survey\Exception;

use App\Domain\Common\Exception\ServiceException;

class AnswersCountIsNotEqualQuestionsCountException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Answers count is not equal questions count.';
    }

    public function getType(): string
    {
        return 'answers_count_is_not_equal_questions_count';
    }
}
