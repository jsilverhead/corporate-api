<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\AddSupervisor;
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
#[CoversClass(AddSupervisor::class)]
final class AddSupervisorTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();
        $department = $this->getService(DepartmentBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/addSupervisor')
            ->withAuthentication($superuser)
            ->withBody([
                'employeeId' => $employee->id->toRfc4122(),
                'departmentId' => $department->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
