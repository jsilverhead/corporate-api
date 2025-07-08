<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\DepartmentWithThisNameAlreadyExists;
use App\Domain\Department\Service\UpdateDepartmentService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateDepartmentService::class)]
final class UpdateDepartmentServiceTest extends BaseWebTestCase
{
    public function testNameAlreadyExistsFail(): void
    {
        $name = 'Отдел АХО';
        $departmentBuilder = $this->getService(DepartmentBuilder::class);
        $department = $departmentBuilder->build();
        $departmentBuilder->withName($name)->build();

        $this->expectException(DepartmentWithThisNameAlreadyExists::class);
        $this->getService(UpdateDepartmentService::class)->update(department: $department, name: $name);
    }

    public function testSuccess(): void
    {
        $name = 'Отдел АХО';
        $department = $this->getService(DepartmentBuilder::class)->build();

        $this->getService(UpdateDepartmentService::class)->update(department: $department, name: $name);

        self::assertSame(expected: $name, actual: $department->name);
    }
}
