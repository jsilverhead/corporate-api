<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Dto;

use DateTimeImmutable;

class TokenPairDto
{
    /**
     * @psalm-param non-empty-string $accessToken
     * @psalm-param non-empty-string $refreshToken
     */
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public DateTimeImmutable $accessTokenExpiresAt,
        public DateTimeImmutable $refreshTokenExpiresAt,
    ) {
    }
}
