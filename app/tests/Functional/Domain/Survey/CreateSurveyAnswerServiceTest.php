<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Survey;

use App\Domain\Survey\Exception\QuestionAlreadyHaveAnswerException;
use App\Domain\Survey\Question;
use App\Domain\Survey\Service\CreateSurveyAnswerService;
use App\Domain\Survey\SurveyAnswer;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\SurveyAnswerBuilder;
use App\Tests\Builder\SurveyBuilder;
use App\Tests\Builder\SurveyTemplateBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateSurveyAnswerService::class)]
final class CreateSurveyAnswerServiceTest extends BaseWebTestCase
{
    public function testAlreadyHaveAnswerFail(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $template = $this->getService(SurveyTemplateBuilder::class)->build();
        /**
         * @psalm-var Question $question
         */
        $question = $template->questions->first();
        $survey = $this->getService(SurveyBuilder::class)->build(employee: $employee, surveyTemplate: $template);
        $this->getService(SurveyAnswerBuilder::class)
            ->withSurvey($survey)
            ->withQuestion($question)
            ->build();

        $this->expectException(QuestionAlreadyHaveAnswerException::class);
        $this->getService(CreateSurveyAnswerService::class)->create(
            survey: $survey,
            question: $question,
            answer: 'Хоббихорсинг',
        );
    }

    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $template = $this->getService(SurveyTemplateBuilder::class)->build();
        /**
         * @psalm-var Question $question
         */
        $question = $template->questions->current();
        $survey = $this->getService(SurveyBuilder::class)->build(employee: $employee, surveyTemplate: $template);

        $answer = $this->getService(CreateSurveyAnswerService::class)->create(
            survey: $survey,
            question: $question,
            answer: 'Хоббихорсинг',
        );

        self::assertInstanceOf(expected: SurveyAnswer::class, actual: $answer);
        self::assertTrue($answer->survey->id->equals($survey->id));
        self::assertTrue($answer->question->id->equals($question->id));
        self::assertTrue($survey->answers->contains($answer));
    }
}
