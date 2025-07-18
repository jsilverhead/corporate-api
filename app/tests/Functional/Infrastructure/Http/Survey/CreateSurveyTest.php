<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Infrastructure\Http\Common\Action\Survey\CreateSurveyTemplate;
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
#[CoversClass(CreateSurveyTemplate::class)]
final class CreateSurveyTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();
        $template = $this->getService(SurveyTemplateBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/createSurvey')
            ->withAuthentication($superuser)
            ->withBody([
                'employeeId' => $employee->id->toRfc4122(),
                'templateId' => $template->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
