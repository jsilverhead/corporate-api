<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Service;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\Repository\AccessTokenRepository;
use App\Domain\User\User;

readonly class CreateAccessTokenService
{
    public function __construct(
        private AccessTokenEncoder $accessTokenEncoder,
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function create(User $user): AccessToken
    {
        $tokenPair = $this->accessTokenEncoder->encode($user->id);

        $accessToken = new AccessToken(
            user: $user,
            accessToken: $tokenPair->accessToken,
            refreshToken: $tokenPair->refreshToken,
            accessTokenExpiresAt: $tokenPair->accessTokenExpiresAt,
            refreshExpiresAt: $tokenPair->refreshTokenExpiresAt,
        );

        $this->accessTokenRepository->add($accessToken);

        return $accessToken;
    }
}
