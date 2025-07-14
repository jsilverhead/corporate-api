<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Repository;

use App\Domain\AccessToken\AccessToken;
use App\Domain\Common\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessToken>
 */
class AccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: AccessToken::class);
    }

    public function add(AccessToken $accessToken): void
    {
        $this->getEntityManager()->persist($accessToken);
    }

    public function getByRefreshToken(string $refreshToken): ?AccessToken
    {
        /**
         * @psalm-var AccessToken|null $accessToken
         */
        $accessToken = $this->createQueryBuilder('at')
            ->where('at.refreshToken = :refreshToken')
            ->setParameter('refreshToken', $refreshToken, Types::TEXT)
            ->getQuery()
            ->getOneOrNullResult();

        return $accessToken;
    }

    public function getWithUserByAccessToken(string $token): ?AccessToken
    {
        /**
         * @psalm-var AccessToken|null $accessToken
         */
        $accessToken = $this->createQueryBuilder('at')
            ->addSelect('em')
            ->join('at.employee', 'em')
            ->where('at.accessToken = :token')
            ->setParameter('token', $token, Types::TEXT)
            ->getQuery()
            ->getOneOrNullResult();

        return $accessToken;
    }
}
