<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\Service\CreateAccessTokenService;
use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;

readonly class AccessTokenBuilder
{
    public function __construct(
        private CreateAccessTokenService $createAccessTokenService,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function build(User $user): AccessToken
    {
        $accessToken = $this->createAccessTokenService->create(user: $user);

        $this->entityManager->flush();

        return $accessToken;
    }
}
