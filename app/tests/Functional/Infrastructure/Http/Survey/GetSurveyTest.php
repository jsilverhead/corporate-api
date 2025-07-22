<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\SurveyAnswerBuilder;
use App\Tests\Builder\SurveyBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
final class GetSurveyTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $survey = $this->getService(SurveyBuilder::class)
            ->asCompleted()
            ->build();
        $this->getService(SurveyAnswerBuilder::class)
            ->withSurvey($survey)
            ->build();

        $this->httpRequest(method: Request::METHOD_GET, url: '/getSurvey')
            ->withAuthentication($employee)
            ->withQuery([
                'id' => $survey->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
