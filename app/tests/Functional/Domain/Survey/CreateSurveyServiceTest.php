<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Survey;

use App\Domain\Survey\Exception\EmployeeAlreadyHasASurveyException;
use App\Domain\Survey\Service\CreateSurveyService;
use App\Domain\Survey\Survey;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\SurveyBuilder;
use App\Tests\Builder\SurveyTemplateBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateSurveyService::class)]
final class CreateSurveyServiceTest extends BaseWebTestCase
{
    public function testEmployeeAlreadyHaveASurveyFail(): void
    {
        $survey = $this->getService(SurveyBuilder::class)->build();
        $template = $this->getService(SurveyTemplateBuilder::class)->build();

        $this->expectException(EmployeeAlreadyHasASurveyException::class);
        $this->getService(CreateSurveyService::class)->create(employee: $survey->employee, template: $template);
    }

    public function testSuccess(): void
    {
        $template = $this->getService(SurveyTemplateBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $survey = $this->getService(CreateSurveyService::class)->create(employee: $employee, template: $template);

        self::assertInstanceOf(expected: Survey::class, actual: $survey);
        self::assertTrue($survey->template->id->equals($template->id));
        self::assertTrue($survey->employee->id->equals($employee->id));
    }
}
