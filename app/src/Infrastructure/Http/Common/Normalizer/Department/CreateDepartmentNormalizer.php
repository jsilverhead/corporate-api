<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Department;

use App\Domain\Department\Department;

class CreateDepartmentNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Department $department): array
    {
        return [
            'id' => $department->id->toRfc4122(),
        ];
    }
}
