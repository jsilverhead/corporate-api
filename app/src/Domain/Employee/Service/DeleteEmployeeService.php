<?php

declare(strict_types=1);

namespace App\Domain\Employee\Service;

use App\Domain\Common\ValueObject\Email;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Exception\EmployeeAlreadyDeletedException;
use DateTimeImmutable;

class DeleteEmployeeService
{
    public function delete(Employee $employee): void
    {
        if (null !== $employee->deletedAt) {
            throw new EmployeeAlreadyDeletedException();
        }

        $employee->deletedAt = new DateTimeImmutable();
        $employee->email = Email::tryCreateFromString(uniqid(prefix: 'deleted_', more_entropy: true) . '@company.ru');
    }
}
