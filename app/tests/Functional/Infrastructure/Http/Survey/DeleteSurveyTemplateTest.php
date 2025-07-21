<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Infrastructure\Http\Common\Action\Survey\DeleteSurveyTemplate;
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
#[CoversClass(DeleteSurveyTemplate::class)]
final class DeleteSurveyTemplateTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();
        $template = $this->getService(SurveyTemplateBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/deleteSurveyTemplate')
            ->withAuthentication($superuser)
            ->withBody([
                'id' => $template->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
