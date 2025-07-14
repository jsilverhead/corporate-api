<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\AddEmployee;
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
#[CoversClass(AddEmployee::class)]
final class AddEmployeeTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/addEmployee')
            ->withAuthentication($superuser)
            ->withBody([
                'employeeId' => $employee->id->toRfc4122(),
                'departmentId' => $department->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
