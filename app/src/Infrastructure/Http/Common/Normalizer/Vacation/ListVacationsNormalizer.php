<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Vacation;

use App\Domain\Department\Department;
use App\Domain\Employee\Employee;
use App\Domain\Vacation\Vacation;
use App\Infrastructure\Normalizer\DateTimeNormalizer;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListVacationsNormalizer
{
    public function __construct(private DateTimeNormalizer $dateTimeNormalizer)
    {
    }

    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Paginator $paginator): array
    {
        return [
            'items' => array_map(
                fn(Department $department) => [
                    'departmentId' => $department->id->toRfc4122(),
                    'departmentName' => $department->name,
                    'employees' => 0 !== $department->employees->count()
                            ? $this->normalizeEmployees($department->employees->toArray())
                            : null,
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }

    private function normalizeEmployees(array $employees): array
    {
        return array_map(
            fn(Employee $employee) => [
                'employeeId' => $employee->id->toRfc4122(),
                'employeeName' => $employee->name,
                'vacations' => 0 !== $employee->vacations->count()
                        ? $this->normalizeVacations($employee->vacations->toArray())
                        : null,
            ],
            $employees,
        );
    }

    private function normalizeVacations(array $vacations): array
    {
        return array_map(
            fn(Vacation $vacation) => [
                'vacationId' => $vacation->id->toRfc4122(),
                'fromDate' => $this->dateTimeNormalizer->normalize($vacation->fromDate),
                'toDate' => $this->dateTimeNormalizer->normalize($vacation->toDate),
                'isApproved' => $vacation->isApproved,
            ],
            $vacations,
        );
    }
}
