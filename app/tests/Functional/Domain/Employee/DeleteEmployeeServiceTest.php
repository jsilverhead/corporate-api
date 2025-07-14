<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Employee;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Employee\Exception\EmployeeAlreadyDeletedException;
use App\Domain\Employee\Service\DeleteEmployeeService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteEmployeeService::class)]
final class DeleteEmployeeServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $email = Email::tryCreateFromString('olego@company.ru');

        $employee = $this->getService(EmployeeBuilder::class)
            ->withEmail($email)
            ->build();

        $this->getService(DeleteEmployeeService::class)->delete($employee);

        self::assertNotSame(expected: $email, actual: $employee->email);
        self::assertNotNull($employee->deletedAt);
    }

    public function testUserAlreadyDeletedFail(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)
            ->asDeleted()
            ->build();

        $this->expectException(EmployeeAlreadyDeletedException::class);
        $this->getService(DeleteEmployeeService::class)->delete($employee);
    }
}
