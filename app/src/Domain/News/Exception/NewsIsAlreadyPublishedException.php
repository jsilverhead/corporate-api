<?php

declare(strict_types=1);

namespace App\Domain\News\Exception;

use App\Domain\Common\Exception\ServiceException;

class NewsIsAlreadyPublishedException extends ServiceException
{
    public function getDescription(): string
    {
        return 'News is already published.';
    }

    public function getType(): string
    {
        return 'news_is_already_published';
    }
}
