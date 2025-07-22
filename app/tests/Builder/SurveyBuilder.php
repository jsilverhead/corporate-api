<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Employee\Employee;
use App\Domain\Survey\Service\CreateSurveyService;
use App\Domain\Survey\Survey;
use App\Domain\Survey\SurveyTemplate;
use Doctrine\ORM\EntityManagerInterface;

class SurveyBuilder
{
    private bool $completedSurvey = false;

    public function __construct(
        private readonly CreateSurveyService $createSurveyService,
        private readonly EmployeeBuilder $employeeBuilder,
        private readonly SurveyTemplateBuilder $surveyTemplateBuilder,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function build(?Employee $employee = null, ?SurveyTemplate $surveyTemplate = null): Survey
    {
        if (null === $surveyTemplate) {
            $surveyTemplate = $this->surveyTemplateBuilder->build();
        }

        if (null === $employee) {
            $employee = $this->employeeBuilder->build();
        }

        $survey = $this->createSurveyService->create(employee: $employee, template: $surveyTemplate);

        if ($this->completedSurvey) {
            $survey->isCompleted = true;
        }

        $this->entityManager->flush();

        return $survey;
    }
}
