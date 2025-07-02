<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: User::class);
    }

    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function getById(Uuid $id): ?User
    {
        /**
         * @psalm-var User|null $user
         */
        $user = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id, UuidType::NAME)
            ->getQuery()
            ->getOneOrNullResult();

        return $user;
    }

    public function getByIdOrFail(Uuid $id): User
    {
        $user = $this->getById($id);

        // TODO: посмотреть что с хэндлером
        if (null === $user) {
            throw new EntityNotFoundException();
        }

        return $user;
    }

    public function isUserWithEmailExists(Email $email): bool
    {
        return (bool) $this->createQueryBuilder('u')
            ->select('1')
            ->where('u.email = :email')
            ->setParameter('email', $email->email, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @psalm-param list<string>|null $searchWords
     *
     * @psalm-return Paginator<User>
     */
    public function listUsers(int $count, int $offset, ?array $searchWords): Paginator
    {
        $users = $this->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($count);

        if (null !== $searchWords) {
            foreach ($searchWords as $index => $searchWord) {
                $paramName = "word_{$index}";
                $users
                    ->orWhere('LOWER(u.name) LIKE :' . $paramName)
                    ->setParameter($paramName, '%' . mb_strtolower($searchWord) . '%', Types::STRING);
            }
        }

        $users->andWhere('u.deletedAt IS NULL');

        /**
         * @psalm-var Paginator<User> $paginator
         */
        $paginator = new Paginator($users->getQuery());

        return $paginator;
    }
}
