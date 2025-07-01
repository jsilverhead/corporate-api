<?php

declare(strict_types=1);

namespace App\Domain\AccessToken;

class JwtAuthSettings
{
    public function __construct(
        public string $secret,
        public string $algorithm,
        public int $accessTokenTimeOfLife,
        public int $refreshTokenTimeOfLife,
    ) {
    }
}
