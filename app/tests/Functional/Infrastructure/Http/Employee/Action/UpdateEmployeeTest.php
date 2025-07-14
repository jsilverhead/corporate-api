<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Employee\Action;

use App\Infrastructure\Http\Common\Action\Employee\UpdateEmployee;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateEmployee::class)]
final class UpdateEmployeeTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employeeBuilder = $this->getService(EmployeeBuilder::class);
        $employee = $employeeBuilder->build();
        $superuser = $employeeBuilder->asSuperUser()->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/updateEmployee')
            ->withAuthentication($superuser)
            ->withBody([
                'employeeId' => $employee->id->toRfc4122(),
                'name' => 'Олег Разумович',
                'role' => 'superuser',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
