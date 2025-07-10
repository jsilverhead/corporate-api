<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\UserAlreadySupervisingThisDepartmentException;
use App\Domain\Department\Service\AddSupervisorService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\UserBuilder;
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
        $user = $this->getService(UserBuilder::class)->build();

        $this->getService(AddSupervisorService::class)->add(supervisor: $user, department: $department);

        self::assertTrue($user->supervising?->id->equals($department->id));
        self::assertTrue($department->supervisors->contains($user));
    }

    public function testUserAlreadySupervisingDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $user = $this->getService(UserBuilder::class)
            ->withSupervising($department)
            ->build();

        $this->expectException(UserAlreadySupervisingThisDepartmentException::class);
        $this->getService(AddSupervisorService::class)->add(supervisor: $user, department: $department);
    }
}
