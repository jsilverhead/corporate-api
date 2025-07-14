<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Department\Department;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Service\CreateEmployeeService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeBuilder
{
    private ?DateTimeImmutable $birthDate = null;

    private ?Department $department = null;

    private ?Email $email = null;

    private bool $isDeleted = false;

    /** @psalm-var non-empty-string|null $name */
    private ?string $name = null;

    /** @psalm-var non-empty-string|null $password */
    private ?string $password = null;

    private RolesEnum $role = RolesEnum::USER;

    private ?Department $supervising = null;

    public function __construct(
        private CreateEmployeeService $createUserService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function asDeleted(): self
    {
        $this->isDeleted = true;

        return $this;
    }

    public function asSuperUser(): self
    {
        $this->role = RolesEnum::SUPERUSER;

        return $this;
    }

    public function build(): Employee
    {
        $employee = $this->createUserService->create(
            name: $this->name ?? 'Олег Олегович',
            email: $this->email ?? Email::tryCreateFromString(uniqid(more_entropy: true) . '@company.ru'),
            password: $this->password ?? 'Password123',
            role: $this->role,
            birthDate: $this->birthDate,
        );

        if ($this->isDeleted) {
            $employee->deletedAt = new DateTimeImmutable();
        }

        if (null !== $this->supervising) {
            $employee->supervising = $this->supervising;
            $this->supervising->supervisors->add($employee);
        }

        if (null !== $this->department) {
            $employee->department = $this->department;
            $this->department->employees->add($employee);
        }

        $this->entityManager->flush();

        return $employee;
    }

    public function withBirthDate(DateTimeImmutable $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function withDepartment(Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function withEmail(Email $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @psalm-param non-empty-string $password
     */
    public function withPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function withSupervising(Department $department): self
    {
        $this->supervising = $department;

        return $this;
    }
}
