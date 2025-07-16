<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Department;

use App\Domain\Department\Exception\DepartmentAlreadyDeletedException;
use App\Domain\Department\Service\DeleteDepartmentService;
use App\Domain\Employee\Repository\EmployeeRepository;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteDepartmentService::class)]
final class DeleteDepartmentServiceTest extends BaseWebTestCase
{
    public function testAlreadyDeletedDepartmentFail(): void
    {
        $department = $this->getService(DepartmentBuilder::class)
            ->asDeleted()
            ->build();

        self::assertNotNull($department->deletedAt);

        $this->expectException(DepartmentAlreadyDeletedException::class);
        $this->getService(DeleteDepartmentService::class)->delete($department);
    }

    public function testDeleteWithEmployeesAndSupervisorsSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)
            ->withDepartment($department)
            ->build();
        $supervisor = $this->getService(EmployeeBuilder::class)
            ->withSupervising($department)
            ->build();

        self::assertTrue($employee->department?->id->equals($department->id));
        self::assertTrue($supervisor->supervising?->id->equals($department->id));
        self::assertTrue($department->employees->contains($employee));
        self::assertTrue($department->supervisors->contains($supervisor));

        $this->getService(DeleteDepartmentService::class)->delete($department);

        $this->getService(EntityManagerInterface::class)->flush();
        $this->getService(EntityManagerInterface::class)->clear();

        self::assertFalse($department->employees->contains($employee));
        self::assertFalse($department->supervisors->contains($supervisor));

        $updatedEmployee = $this->getService(EmployeeRepository::class)->getByIdOrFail($employee->id);
        $updatedSupervisor = $this->getService(EmployeeRepository::class)->getByIdOrFail($supervisor->id);

        self::assertNull($updatedEmployee->department);
        self::assertNull($updatedSupervisor->supervising);
    }

    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();

        self::assertNull($department->deletedAt);

        $this->getService(DeleteDepartmentService::class)->delete($department);

        /** @psalm-suppress DocblockTypeContradiction */
        self::assertInstanceOf(expected: DateTimeImmutable::class, actual: $department->deletedAt);
    }
}
