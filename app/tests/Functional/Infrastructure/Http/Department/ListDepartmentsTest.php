<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\ListDepartments;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListDepartments::class)]
final class ListDepartmentsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $departmentBuilder = $this->getService(DepartmentBuilder::class);
        $departmentBuilder->build();
        $departmentBuilder->build();
        $departmentBuilder->withName('АХО')->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listDepartments')
            ->withAuthentication($employee)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
                'filter' => [
                    'search' => 'Отдел АХО',
                ],
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
        $this->assertCountOfItemsInResponse(response: $response, expectedCount: 1);
    }
}
