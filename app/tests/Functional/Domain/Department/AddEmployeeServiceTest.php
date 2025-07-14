<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\EmployeeAlreadyInTheDepartmentException;
use App\Domain\Department\Service\AddEmployeeService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(AddEmployeeService::class)]
final class AddEmployeeServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->getService(AddEmployeeService::class)->add(employee: $employee, department: $department);

        self::assertTrue($employee->department?->id->equals($department->id));
        self::assertTrue($department->employees->contains($employee));
    }

    public function testUserAlreadyInDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)
            ->withDepartment($department)
            ->build();

        $this->expectException(EmployeeAlreadyInTheDepartmentException::class);
        $this->getService(AddEmployeeService::class)->add(employee: $employee, department: $department);
    }
}
