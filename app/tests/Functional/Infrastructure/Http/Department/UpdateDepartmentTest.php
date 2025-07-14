<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\UpdateDepartment;
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
#[CoversClass(UpdateDepartment::class)]
final class UpdateDepartmentTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();
        $department = $this->getService(DepartmentBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/updateDepartment')
            ->withAuthentication($superuser)
            ->withBody([
                'id' => $department->id->toRfc4122(),
                'name' => 'Чилловый департамент',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
