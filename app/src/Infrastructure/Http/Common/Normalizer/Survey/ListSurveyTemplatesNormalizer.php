<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\Survey;

use App\Domain\Survey\SurveyTemplate;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListSurveyTemplatesNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(Paginator $paginator): array
    {
        return [
            'items' => array_map(
                static fn(SurveyTemplate $template) => [
                    'id' => $template->id->toRfc4122(),
                    'name' => $template->name,
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }
}
