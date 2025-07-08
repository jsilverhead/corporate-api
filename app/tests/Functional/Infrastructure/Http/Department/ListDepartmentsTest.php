<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\ListDepartments;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\UserBuilder;
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
        $user = $this->getService(UserBuilder::class)->build();
        $departmentBuilder = $this->getService(DepartmentBuilder::class);
        $departmentBuilder->build();
        $departmentBuilder->build();
        $departmentBuilder->withName('АХО')->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listDepartments')
            ->withAuthentication($user)
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
