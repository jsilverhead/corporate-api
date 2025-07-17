<?php

declare(strict_types=1);

namespace App\Domain\Survey\Exception;

use App\Domain\Common\Exception\ServiceException;

class EmployeeAlreadyHasASurveyException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Employee already has a survey.';
    }

    public function getType(): string
    {
        return 'employee_already_has_survey';
    }
}
