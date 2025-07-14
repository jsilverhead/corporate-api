<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Employee\Action;

use App\Infrastructure\Http\Common\Action\Employee\ListEmployees;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListEmployees::class)]
final class ListEmployeesTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employeeBuilder = $this->getService(EmployeeBuilder::class);
        $employeeBuilder->withName('Цейхович Олег')->build();
        $employeeBuilder->withName('Никитин Олег')->build();

        $superuser = $employeeBuilder->asSuperUser()->withName('Нуркова Оксана')->build();

        $employeeBuilder->asDeleted()->withName('Олегов Семён')->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listEmployees')
            ->withAuthentication($superuser)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
                'filter' => [
                    'search' => 'олег',
                ],
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
        $this->assertCountOfItemsInResponse(response: $response, expectedCount: 2);
    }
}
