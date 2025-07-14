<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\EmployeeAlreadySupervisingThisDepartmentException;
use App\Domain\Department\Service\AddSupervisorService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(AddSupervisorService::class)]
final class AddSupervisorServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->getService(AddSupervisorService::class)->add(supervisor: $employee, department: $department);

        self::assertTrue($employee->supervising?->id->equals($department->id));
        self::assertTrue($department->supervisors->contains($employee));
    }

    public function testUserAlreadySupervisingDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)
            ->withSupervising($department)
            ->build();

        $this->expectException(EmployeeAlreadySupervisingThisDepartmentException::class);
        $this->getService(AddSupervisorService::class)->add(supervisor: $employee, department: $department);
    }
}
