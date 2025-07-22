<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Survey;

use App\Domain\Survey\Survey;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListSurveysNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Paginator $paginator): array
    {
        return [
            'items' => array_map(
                static fn(Survey $survey): array => [
                    'surveyId' => $survey->id->toRfc4122(),
                    'isCompleted' => $survey->isCompleted,
                    'employee' => [
                        'employeeId' => $survey->employee->id->toRfc4122(),
                        'name' => $survey->employee->name,
                    ],
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsCount' => $paginator->count(),
        ];
    }
}
