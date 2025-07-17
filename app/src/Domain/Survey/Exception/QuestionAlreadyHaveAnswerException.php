<?php

declare(strict_types=1);

namespace App\Domain\Survey\Exception;

use App\Domain\Common\Exception\ServiceException;

class QuestionAlreadyHaveAnswerException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Question already have answer.';
    }

    public function getType(): string
    {
        return 'question_already_have_answer';
    }
}
