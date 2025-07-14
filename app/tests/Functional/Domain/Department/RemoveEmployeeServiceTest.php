<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\EmployeeIsNotInTheDepartmentException;
use App\Domain\Department\Service\RemoveEmployeeService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(RemoveEmployeeService::class)]
final class RemoveEmployeeServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)
            ->withDepartment($department)
            ->build();

        $this->getService(RemoveEmployeeService::class)->remove(department: $department, employee: $employee);

        self::assertNull($employee->department);
        self::assertFalse($department->employees->contains($employee));
    }

    public function testUserNotInDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->expectException(EmployeeIsNotInTheDepartmentException::class);
        $this->getService(RemoveEmployeeService::class)->remove(department: $department, employee: $employee);
    }
}
