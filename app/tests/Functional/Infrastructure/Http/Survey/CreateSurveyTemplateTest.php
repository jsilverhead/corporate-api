<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Survey;

use App\Infrastructure\Http\Common\Action\Survey\CreateSurveyTemplate;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateSurveyTemplate::class)]
final class CreateSurveyTemplateTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $questions = ['Расскажите о своих хобби?', 'Есть ли у вас домашнее животное? Расскажите о нём.'];

        $this->httpRequest(method: Request::METHOD_POST, url: '/createSurveyTemplate')
            ->withAuthentication($superuser)
            ->withBody([
                'questions' => $questions,
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
