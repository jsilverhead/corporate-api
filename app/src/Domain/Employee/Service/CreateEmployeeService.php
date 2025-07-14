<?php

declare(strict_types=1);

namespace App\Domain\Employee\Service;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Department\Department;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\Employee\Exception\EmployeeWithThisEmailAlreadyExistsException;
use App\Domain\Employee\Repository\EmployeeRepository;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class CreateEmployeeService
{
    public function __construct(
        private EmployeeRepository $employeeRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    /**
     * @psalm-param non-empty-string $name
     * @psalm-param non-empty-string $password
     */
    public function create(
        string $name,
        Email $email,
        string $password,
        RolesEnum $role,
        ?DateTimeImmutable $birthDate,
        ?Department $department = null,
        ?Department $supervising = null,
    ): Employee {
        $isUserWithEmailExists = $this->employeeRepository->isEmployeeWithEmailExists($email);

        if ($isUserWithEmailExists) {
            throw new EmployeeWithThisEmailAlreadyExistsException();
        }

        $employee = new Employee(
            name: $name,
            email: $email,
            role: $role,
            birthDate: $birthDate,
            department: $department,
            supervising: $supervising,
        );

        /**
         * @psalm-var non-empty-string $hashedPassword
         */
        $hashedPassword = $this->passwordHasher->hashPassword($employee, $password);

        $employee->password = $hashedPassword;

        $this->employeeRepository->add($employee);

        return $employee;
    }
}
