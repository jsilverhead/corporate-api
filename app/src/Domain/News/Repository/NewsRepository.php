<?php

declare(strict_types=1);

namespace App\Domain\News\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\News\News;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<News> */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: News::class);
    }

    public function add(News $news): void
    {
        $this->getEntityManager()->persist($news);
    }
}
