<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Common\Repository\ServiceEntityRepository;
use App\Domain\Common\ValueObject\Email;
use App\Domain\User\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

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

    public function isUserWithEmailExists(Email $email): bool
    {
        return (bool) $this->createQueryBuilder('u')
            ->select('1')
            ->where('u.email = :email')
            ->setParameter('email', $email->email, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
