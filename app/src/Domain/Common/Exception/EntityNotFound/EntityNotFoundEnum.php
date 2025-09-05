<?php

declare(strict_types=1);

namespace App\Domain\Common\Exception\EntityNotFound;

enum EntityNotFoundEnum: string
{
    case DEPARTMENT = 'department';
    case EMPLOYEE = 'employee';
    case QUESTION = 'question';
    case SURVEY = 'survey';
    case SURVEY_TEMPLATE = 'survey_template';
    case VACATION = 'vacation';
}
