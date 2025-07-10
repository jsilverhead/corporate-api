<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\UserDoNotSupervisingThisDepartmentException;
use App\Domain\Department\Service\RemoveSupervisorService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\UserBuilder;
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
        $user = $this->getService(UserBuilder::class)
            ->withSupervising($department)
            ->build();

        $this->getService(RemoveSupervisorService::class)->remove(department: $department, supervisor: $user);

        self::assertNull($user->supervising);
        self::assertFalse($department->supervisors->contains($user));
    }

    public function testUserNotSupervisingDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $user = $this->getService(UserBuilder::class)->build();

        $this->expectException(UserDoNotSupervisingThisDepartmentException::class);
        $this->getService(RemoveSupervisorService::class)->remove(department: $department, supervisor: $user);
    }
}
