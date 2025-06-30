<?php

declare(strict_types=1);

namespace App\Domain\Common\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\LazyServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of object
 * @template-extends LazyServiceEntityRepository<T>
 *
 * @psalm-suppress InternalClass
 *
 * todo: удалить класс когда разработчики psalm закроют issues https://github.com/spiks/velo-api/issues/27
 */
class ServiceEntityRepository extends LazyServiceEntityRepository
{
    /**
     * @psalm-param class-string $entityClass
     *
     * @psalm-suppress InternalMethod
     */
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }
}
