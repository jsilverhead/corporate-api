<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\News;

use App\Domain\Employee\Employee;
use App\Domain\News\News;
use App\Infrastructure\Normalizer\DateTimeNormalizer;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ListNewsNormalizer
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
                fn(News $news): array => [
                    'id' => $news->id->toRfc4122(),
                    'title' => $news->title,
                    'preview' => $this->normalizePreview($news),
                    'createdAt' => $this->dateTimeNormalizer->normalize($news->createdAt),
                    'isPublished' => $news->published,
                    'author' => $this->denormalizeAuthor($news->author),
                ],
                (array) $paginator->getIterator(),
            ),
            'itemsAmount' => $paginator->count(),
        ];
    }

    private function denormalizeAuthor(Employee $author): array
    {
        return [
            'id' => $author->id->toRfc4122(),
            'name' => $author->name,
        ];
    }

    private function normalizePreview(News $news): string
    {
        $preview = $news->content;

        if (mb_strlen($preview) < 100) {
            return $preview;
        }

        $preview = mb_substr(string: $news->content, start: 0, length: 97);
        $preview .= '...';

        return $preview;
    }
}
