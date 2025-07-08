<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\DepartmentWithThisNameAlreadyExists;
use App\Domain\Department\Service\CreateDepartmentService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateDepartmentService::class)]
final class CreateDepartmentServiceTest extends BaseWebTestCase
{
    public function testNameAlreadyExistsFail(): void
    {
        $name = 'Отел HR';
        $this->getService(DepartmentBuilder::class)
            ->withName($name)
            ->build();

        $this->expectException(DepartmentWithThisNameAlreadyExists::class);
        $this->getService(CreateDepartmentService::class)->create($name);
    }

    public function testSuccess(): void
    {
        $name = 'Отдел HR';

        $department = $this->getService(CreateDepartmentService::class)->create($name);

        self::assertSame(expected: $name, actual: $department->name);
    }
}
