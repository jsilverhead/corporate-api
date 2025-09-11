<?php

declare(strict_types=1);

namespace App\Domain\News\Service;

use App\Domain\Common\Exception\AccessIsDeniedException;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\News;
use App\Domain\News\Repository\NewsRepository;

class DeleteNewsService
{
    public function __construct(private NewsRepository $newsRepository)
    {
    }

    public function delete(News $news, Employee $remover): void
    {
        if (false === $news->author->id->equals($remover->id) && RolesEnum::SUPERUSER !== $remover->role) {
            throw new AccessIsDeniedException();
        }

        $this->newsRepository->remove($news);
    }
}
