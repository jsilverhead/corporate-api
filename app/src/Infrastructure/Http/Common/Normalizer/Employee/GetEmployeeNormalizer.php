<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Employee;

use App\Domain\Employee\Employee;

class GetEmployeeNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Employee $employee): array
    {
        $department = null;

        if (null !== $employee->department) {
            $department = ['id' => $employee->department->id->toRfc4122(), 'name' => $employee->department->name];
        }

        $supervising = null;

        if (null !== $employee->supervising) {
            $supervising = ['id' => $employee->supervising->id->toRfc4122(), 'name' => $employee->supervising->name];
        }

        return [
            'id' => $employee->id->toRfc4122(),
            'name' => $employee->name,
            'email' => $employee->email->email,
            'role' => $employee->role->value,
            'birthDate' => $employee->birthDate?->format('Y-m-d'),
            'department' => $department,
            'supervising' => $supervising,
        ];
    }
}
