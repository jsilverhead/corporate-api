<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Employee;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Exception\EmployeeWithThisEmailAlreadyExistsException;
use App\Domain\Employee\Service\CreateEmployeeService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateEmployeeService::class)]
final class CreateEmployeeServiceTest extends BaseWebTestCase
{
    public function testEmailAlreadyExistsFail(): void
    {
        $email = Email::tryCreateFromString('olego@company.ru');
        $this->getService(EmployeeBuilder::class)
            ->withEmail($email)
            ->build();

        $this->expectException(EmployeeWithThisEmailAlreadyExistsException::class);
        $this->getService(CreateEmployeeService::class)->create(
            name: 'Олегов Олег',
            email: $email,
            password: 'Password123',
            role: RolesEnum::USER,
            birthDate: null,
        );
    }

    public function testSuccess(): void
    {
        $name = 'Олегов Олег';
        $email = Email::tryCreateFromString('olego@company.ru');
        $password = 'Password123';
        $birthDate = new DateTimeImmutable('2000-01-01');
        $role = RolesEnum::USER;

        $employee = $this->getService(CreateEmployeeService::class)->create(
            name: $name,
            email: $email,
            password: $password,
            role: $role,
            birthDate: $birthDate,
        );

        self::assertSame(expected: $name, actual: $employee->name);
        self::assertSame(expected: $email, actual: $employee->email);
        self::assertSame(expected: $role, actual: $employee->role);
        self::assertSame(expected: $birthDate, actual: $employee->birthDate);
    }
}
