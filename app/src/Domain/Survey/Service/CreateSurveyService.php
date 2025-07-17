<?php

declare(strict_types=1);

namespace App\Domain\Survey\Service;

use App\Domain\Employee\Employee;
use App\Domain\Survey\Exception\EmployeeAlreadyHasASurveyException;
use App\Domain\Survey\Repository\SurveyRepository;
use App\Domain\Survey\Survey;
use App\Domain\Survey\SurveyTemplate;

readonly class CreateSurveyService
{
    public function __construct(private SurveyRepository $surveyRepository)
    {
    }

    public function create(Employee $employee, SurveyTemplate $template): Survey
    {
        if (null !== $employee->survey) {
            throw new EmployeeAlreadyHasASurveyException();
        }

        $survey = new Survey(template: $template, employee: $employee);
        $employee->survey = $survey;

        $this->surveyRepository->add($survey);

        return $survey;
    }
}
