<?php

declare(strict_types=1);

namespace App\Domain\Survey\Exception;

use App\Domain\Common\Exception\ServiceException;

class SurveyIsAlreadyCompletedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Survey is already completed.';
    }

    public function getType(): string
    {
        return 'survey_is_already_completed';
    }
}
