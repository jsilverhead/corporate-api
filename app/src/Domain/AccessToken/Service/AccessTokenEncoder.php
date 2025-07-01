<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Service;

use App\Domain\AccessToken\Dto\TokenPairDto;
use App\Domain\AccessToken\JwtAuthSettings;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Symfony\Component\Uid\Uuid;

readonly class AccessTokenEncoder
{
    public function __construct(private JwtAuthSettings $jwtAuthSettings)
    {
    }

    public function encode(Uuid $userId): TokenPairDto
    {
        $now = new DateTimeImmutable();

        /**
         * @psalm-var DateTimeImmutable $accessTokenExpiresAt
         */
        $accessTokenExpiresAt = $now->modify(sprintf('+%s seconds', $this->jwtAuthSettings->accessTokenTimeOfLife));

        /**
         * @psalm-var DateTimeImmutable $refreshTokenExpiresAt
         */
        $refreshTokenExpiresAt = $now->modify(sprintf('+%s seconds', $this->jwtAuthSettings->refreshTokenTimeOfLife));

        $accessTokenPayload = [
            'iss' => 'Corporate API',
            'iat' => $now->getTimestamp(),
            'exp' => $accessTokenExpiresAt->getTimestamp(),
            'userId' => $userId->toRfc4122(),
            'salt' => $this->getSalt(),
        ];

        $refreshTokenPayload = [
            'iss' => 'Corporate API',
            'iat' => $now->getTimestamp(),
            'exp' => $refreshTokenExpiresAt->getTimestamp(),
            'userId' => $userId->toRfc4122(),
            'salt' => $this->getSalt(),
        ];

        /**
         * @psalm-var non-empty-string $accessToken
         */
        $accessToken = JWT::encode(
            payload: $accessTokenPayload,
            key: $this->jwtAuthSettings->secret,
            alg: $this->jwtAuthSettings->algorithm,
        );

        /**
         * @psalm-var non-empty-string $refreshToken
         */
        $refreshToken = JWT::encode(
            payload: $refreshTokenPayload,
            key: $this->jwtAuthSettings->secret,
            alg: $this->jwtAuthSettings->algorithm,
        );

        return new TokenPairDto(
            accessToken: $accessToken,
            refreshToken: $refreshToken,
            accessTokenExpiresAt: $accessTokenExpiresAt,
            refreshTokenExpiresAt: $refreshTokenExpiresAt,
        );
    }

    private function getSalt(): string
    {
        return uniqid(more_entropy: true);
    }
}
