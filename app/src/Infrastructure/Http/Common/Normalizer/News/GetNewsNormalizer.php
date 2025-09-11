<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\News;

use App\Domain\News\News;

class GetNewsNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(News $news): array
    {
        $author = [
            'id' => $news->author->id->toRfc4122(),
            'name' => $news->author->name,
        ];

        return [
            'id' => $news->id->toRfc4122(),
            'title' => $news->title,
            'content' => $news->content,
            'author' => $author,
            'isPublished' => $news->published,
        ];
    }
}
