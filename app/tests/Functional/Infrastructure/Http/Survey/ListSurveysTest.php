<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Infrastructure\Http\Common\Action\Survey\ListSurveys;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\SurveyBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListSurveys::class)]
final class ListSurveysTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();
        $surveyBuilder = $this->getService(SurveyBuilder::class);
        $surveyBuilder->build();
        $surveyBuilder->build();
        $surveyBuilder->asCompleted()->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listSurveys')
            ->withAuthentication($superuser)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
                'status' => 'completed',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
        $this->assertCountOfItemsInResponse(response: $response, expectedCount: 1);
    }
}
