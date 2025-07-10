<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\UserAlreadyInTheDepartmentException;
use App\Domain\Department\Service\AddEmployeeService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\UserBuilder;
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
        $user = $this->getService(UserBuilder::class)->build();

        $this->getService(AddEmployeeService::class)->add(employee: $user, department: $department);

        self::assertTrue($user->department?->id->equals($department->id));
        self::assertTrue($department->employees->contains($user));
    }

    public function testUserAlreadyInDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $user = $this->getService(UserBuilder::class)
            ->withDepartment($department)
            ->build();

        $this->expectException(UserAlreadyInTheDepartmentException::class);
        $this->getService(AddEmployeeService::class)->add(employee: $user, department: $department);
    }
}
