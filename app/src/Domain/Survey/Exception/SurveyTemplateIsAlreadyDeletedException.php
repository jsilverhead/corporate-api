<?php

declare(strict_types=1);

namespace App\Domain\Survey\Exception;

use App\Domain\Common\Exception\ServiceException;

class SurveyTemplateIsAlreadyDeletedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Survey template is already deleted.';
    }

    public function getType(): string
    {
        return 'survey_template_is_already_deleted';
    }
}
