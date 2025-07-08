<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Department;

use App\Domain\Department\Department;

class GetDepartmentNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Department $department): array
    {
        return [
            'id' => $department->id->toRfc4122(),
            'name' => $department->name,
        ];
    }
}
