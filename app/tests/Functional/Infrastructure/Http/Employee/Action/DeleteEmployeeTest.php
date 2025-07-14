<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Employee\Action;

use App\Infrastructure\Http\Common\Action\Employee\DeleteEmployee;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteEmployee::class)]
final class DeleteEmployeeTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employeeBuilder = $this->getService(EmployeeBuilder::class);
        $employee = $employeeBuilder->build();
        $superuser = $employeeBuilder->asSuperUser()->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/deleteEmployee')
            ->withAuthentication($superuser)
            ->withBody([
                'id' => $employee->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
