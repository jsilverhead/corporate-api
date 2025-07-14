<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Service;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\Repository\AccessTokenRepository;
use App\Domain\Employee\Employee;

readonly class CreateAccessTokenService
{
    public function __construct(
        private AccessTokenEncoder $accessTokenEncoder,
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function create(Employee $employee): AccessToken
    {
        $tokenPair = $this->accessTokenEncoder->encode($employee->id);

        $accessToken = new AccessToken(
            employee: $employee,
            accessToken: $tokenPair->accessToken,
            refreshToken: $tokenPair->refreshToken,
            accessTokenExpiresAt: $tokenPair->accessTokenExpiresAt,
            refreshExpiresAt: $tokenPair->refreshTokenExpiresAt,
        );

        $this->accessTokenRepository->add($accessToken);

        return $accessToken;
    }
}
