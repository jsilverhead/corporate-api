<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Employee;

use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Service\UpdateEmployeeService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateEmployeeService::class)]
final class UpdateEmployeeServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $newName = 'Олег Назарович';
        $newRole = RolesEnum::SUPERUSER;

        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->getService(UpdateEmployeeService::class)->update(employee: $employee, name: $newName, role: $newRole);

        self::assertSame(expected: $newName, actual: $employee->name);
        self::assertSame(expected: $newRole, actual: $employee->role);
    }
}
