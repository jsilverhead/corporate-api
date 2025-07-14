<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Employee;

use App\Domain\Employee\Employee;

class CreateEmployeeNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Employee $employee): array
    {
        return [
            'id' => $employee->id->toRfc4122(),
        ];
    }
}
