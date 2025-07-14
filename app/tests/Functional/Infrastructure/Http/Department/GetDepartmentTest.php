<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\GetDepartment;
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
#[CoversClass(GetDepartment::class)]
final class GetDepartmentTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $department = $this->getService(DepartmentBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_GET, url: '/getDepartment')
            ->withAuthentication($employee)
            ->withQuery([
                'id' => $department->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
