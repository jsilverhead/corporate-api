<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Vacation;

use App\Domain\Department\Department;
use App\Domain\Employee\Employee;
use App\Domain\Vacation\Vacation;
use App\Infrastructure\Normalizer\DateTimeNormalizer;
use Doctrine\Common\Collections\Collection;
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
        $checkAndNormalizeEmployees = function (Collection $employees): ?array {
            if (0 !== $employees->count()) {
                return $this->normalizeEmployees($employees->toArray());
            }

            return null;
        };

        return [
            'items' => array_map(
                static fn(Department $department) => [
                    'departmentId' => $department->id->toRfc4122(),
                    'departmentName' => $department->name,
                    'employees' => $checkAndNormalizeEmployees($department->employees),
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }

    private function normalizeEmployees(array $employees): array
    {
        $checkAndNormalizeVacations = function (Collection $vacations): ?array {
            if (0 !== $vacations->count()) {
                return $this->normalizeVacations($vacations->toArray());
            }

            return null;
        };

        return array_map(
            static fn(Employee $employee) => [
                'employeeId' => $employee->id->toRfc4122(),
                'employeeName' => $employee->name,
                'vacations' => $checkAndNormalizeVacations($employee->vacations),
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
