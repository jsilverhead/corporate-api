<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\RemoveEmployee;
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
#[CoversClass(RemoveEmployee::class)]
final class RemoveEmployeeTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $userBuilder = $this->getService(EmployeeBuilder::class)->withDepartment($department);
        $employee = $userBuilder->build();
        $superuser = $userBuilder->asSuperUser()->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/removeEmployee')
            ->withAuthentication($superuser)
            ->withBody([
                'departmentId' => $department->id->toRfc4122(),
                'employeeId' => $employee->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
