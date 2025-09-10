<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\News;

use App\Infrastructure\Http\Common\Action\News\AddNews;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(AddNews::class)]
final class AddNewsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/addNews')
            ->withAuthentication($employee)
            ->withBody([
                'title' => 'Выход нового сотрудника',
                'content' => 'Поздравляю со становлением частью нашей команды',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
