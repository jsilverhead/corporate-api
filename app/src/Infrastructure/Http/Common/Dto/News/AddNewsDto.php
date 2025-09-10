<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\News;

readonly class AddNewsDto
{
    /**
     * @psalm-param non-empty-string $title
     * @psalm-param non-empty-string $content
     */
    public function __construct(public string $title, public string $content)
    {
    }
}
