<?php

declare(strict_types=1);

namespace App\Domain\News\Repository;

use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundEnum;
use App\Domain\Common\Exception\EntityNotFound\EntityNotFoundException;
use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Enum\NewsOrderDirectionEnum;
use App\Domain\News\News;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

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

    public function getById(Uuid $id): ?News
    {
        /** @psalm-var News|null $news */
        $news = $this->createQueryBuilder('n')
            ->where('n.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $news;
    }

    public function getByIdOrFail(Uuid $id): News
    {
        $news = $this->getById($id);

        if (null === $news) {
            throw new EntityNotFoundException(EntityNotFoundEnum::NEWS);
        }

        return $news;
    }

    public function getByIdWithAuthorOrFail(Uuid $id, Employee $employee): News
    {
        /** @psalm-var News|null $news */
        $news = $this->createQueryBuilder('n')
            ->select('n', 'a')
            ->join('n.author', 'a')
            ->where('n.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->andWhere('n.author = :authorId OR n.published = true')
            ->setParameter('authorId', $employee->id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $news) {
            throw new EntityNotFoundException(EntityNotFoundEnum::NEWS);
        }

        return $news;
    }

    /**
     * @psalm-return Paginator<News>
     */
    public function list(int $count, int $offset, NewsOrderDirectionEnum $directionEnum, Employee $requester): Paginator
    {
        $news = $this->createQueryBuilder('n')
            ->select('n', 'a')
            ->join('n.author', 'a')
            ->setMaxResults($count)
            ->setFirstResult($offset)
            ->orderBy('n.createdAt', $directionEnum->value);

        if (RolesEnum::USER === $requester->role) {
            $news
                ->andWhere('n.author = :authorId OR n.published = true')
                ->setParameter('authorId', $requester->id, UuidType::NAME);
        }

        /** @psalm-var Paginator<News> $paginator */
        $paginator = new Paginator($news->getQuery());

        return $paginator;
    }

    public function remove(News $news): void
    {
        $this->getEntityManager()->remove($news);
    }
}
