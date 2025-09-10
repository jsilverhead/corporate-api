<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Normalizer\News;

use App\Domain\News\News;

class AddNewsNormalizer
{
    /**
     * @psalm-return non-empty-array
     */
    public function normalize(News $news): array
    {
        return [
            'id' => $news->id->toRfc4122(),
        ];
    }
}
