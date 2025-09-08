<?php

declare(strict_types=1);

namespace App\Domain\News\Service;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\News;
use App\Domain\News\Repository\NewsRepository;

readonly class CreateNewsService
{
    public function __construct(private NewsRepository $newsRepository)
    {
    }

    /**
     * @psalm-param non-empty-string $title
     * @psalm-param non-empty-string $content
     */
    public function create(Employee $author, string $title, string $content): News
    {
        $news = new News(author: $author, title: $title, content: $content);

        if (RolesEnum::SUPERUSER === $author->role) {
            $news->published = true;
        }

        $this->newsRepository->add($news);

        return $news;
    }
}
