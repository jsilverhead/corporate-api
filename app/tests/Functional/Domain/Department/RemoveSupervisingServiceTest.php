<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\EmployeeDoNotSuperviseThisDepartmentException;
use App\Domain\Department\Service\RemoveSupervisorService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(RemoveSupervisorService::class)]
final class RemoveSupervisingServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)
            ->withSupervising($department)
            ->build();

        $this->getService(RemoveSupervisorService::class)->remove(department: $department, supervisor: $employee);

        self::assertNull($employee->supervising);
        self::assertFalse($department->supervisors->contains($employee));
    }

    public function testUserNotSupervisingDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->expectException(EmployeeDoNotSuperviseThisDepartmentException::class);
        $this->getService(RemoveSupervisorService::class)->remove(department: $department, supervisor: $employee);
    }
}
