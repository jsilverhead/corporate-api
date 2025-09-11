<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Dto\News;

use Symfony\Component\Uid\Uuid;

readonly class UpdateNewsDto
{
    public function __construct(public Uuid $id, public string $title, public string $content)
    {
    }
}
