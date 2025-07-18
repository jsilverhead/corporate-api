<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Infrastructure\Http\Common\Action\Survey\ListSurveyTemplates;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\SurveyTemplateBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListSurveyTemplates::class)]
final class ListSurveyTemplatesTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $templateBuilder = $this->getService(SurveyTemplateBuilder::class);
        $templateBuilder->build();
        $templateBuilder->build();
        $templateBuilder->build();

        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listSurveyTemplates')
            ->withAuthentication($superuser)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
        $this->assertCountOfItemsInResponse(response: $response, expectedCount: 3);
    }
}
