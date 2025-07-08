<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Department;

use App\Domain\Department\Department;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListDepartmentsNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Paginator $paginator): array
    {
        return [
            'items' => array_map(
                static fn(Department $department): array => [
                    'id' => $department->id->toRfc4122(),
                    'name' => $department->name,
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }
}
