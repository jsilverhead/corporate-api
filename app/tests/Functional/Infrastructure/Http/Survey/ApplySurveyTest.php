<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Domain\Survey\Question;
use App\Infrastructure\Http\Common\Action\Survey\ApplySurvey;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\SurveyBuilder;
use App\Tests\Builder\SurveyTemplateBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ApplySurvey::class)]
final class ApplySurveyTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $template = $this->getService(SurveyTemplateBuilder::class)
            ->withQuestions(['Есть ли у вас домашнее животное? Расскажите о нём.', 'Какие у вас хобби?'])
            ->build();
        $survey = $this->getService(SurveyBuilder::class)->build(employee: $employee, surveyTemplate: $template);

        /**
         * @psalm-var list<Question> $questions
         */
        $questions = $template->questions->toArray();

        $answers = [];

        foreach ($questions as $question) {
            $answers[] = [
                'questionId' => $question->id->toRfc4122(),
                'answer' => 'Какой-то ответ.',
            ];
        }

        $this->httpRequest(method: Request::METHOD_POST, url: '/applySurvey')
            ->withAuthentication($employee)
            ->withBody([
                'surveyId' => $survey->id->toRfc4122(),
                'answers' => $answers,
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
