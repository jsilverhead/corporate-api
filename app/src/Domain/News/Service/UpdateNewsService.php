<?php

declare(strict_types=1);

namespace App\Domain\News\Service;

use App\Domain\Common\Exception\AccessIsDeniedException;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Exception\NewsIsAlreadyPublishedException;
use App\Domain\News\News;

class UpdateNewsService
{
    public function publish(News $news, Employee $publisher): void
    {
        if (RolesEnum::SUPERUSER !== $publisher->role) {
            throw new AccessIsDeniedException();
        }

        if ($news->published) {
            throw new NewsIsAlreadyPublishedException();
        }

        $news->published = true;
    }

    public function update(News $news, string $title, string $content, Employee $editor): void
    {
        if (false === $news->author->id->equals($editor->id) && RolesEnum::SUPERUSER !== $editor->role) {
            throw new AccessIsDeniedException();
        }

        $news->title = $title;
        $news->content = $content;
    }
}
