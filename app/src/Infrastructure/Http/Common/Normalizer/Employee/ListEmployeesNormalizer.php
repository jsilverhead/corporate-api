<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Employee;

use App\Domain\Employee\Employee;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListEmployeesNormalizer
{
    public function __construct()
    {
    }

    /**
     * @psalm-param Paginator<Employee> $paginator
     *
     * @psalm-return non-empty-array
     */
    public function normalize(Paginator $paginator): array
    {
        return [
            'items' => array_map(
                static fn(Employee $employee): array => [
                    'id' => $employee->id->toRfc4122(),
                    'name' => $employee->name,
                    'email' => $employee->email->email,
                    'role' => $employee->role->value,
                    'department' => $employee->department?->name,
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }
}
