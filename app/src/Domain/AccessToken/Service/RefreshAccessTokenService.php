<?php

declare(strict_types=1);

namespace App\Domain\AccessToken\Service;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\Exception\ExpiredAccessTokenException;
use App\Domain\AccessToken\Exception\ExpiredJwtTokenException;
use App\Domain\AccessToken\Exception\JwtTokenIsInvalidException;
use App\Domain\AccessToken\Exception\UnknownTokenException;
use App\Domain\AccessToken\JwtAuthSettings;
use App\Domain\AccessToken\Repository\AccessTokenRepository;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\Uid\Uuid;
use UnexpectedValueException;

readonly class RefreshAccessTokenService
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
        private AccessTokenEncoder $accessTokenEncoder,
        private JwtAuthSettings $jwtAuthSettings,
    ) {
    }

    /**
     * @psalm-param non-empty-string $refreshToken
     */
    public function refresh(string $refreshToken): AccessToken
    {
        try {
            /**
             * @psalm-var array{
             *     userId: string
             * } $payload
             */
            $payload = (array) JWT::decode(
                jwt: $refreshToken,
                keyOrKeyArray: new Key(
                    keyMaterial: $this->jwtAuthSettings->secret,
                    algorithm: $this->jwtAuthSettings->algorithm,
                ),
            );
        } catch (ExpiredException) {
            throw new ExpiredJwtTokenException();
        } catch (SignatureInvalidException | UnexpectedValueException) {
            throw new JwtTokenIsInvalidException();
        }

        if (!\array_key_exists('userId', $payload)) {
            throw new JwtTokenIsInvalidException();
        }

        $accessToken = $this->accessTokenRepository->getByRefreshToken($refreshToken);

        if (null === $accessToken) {
            throw new UnknownTokenException();
        }

        $userId = Uuid::fromString($payload['userId']);
        $tokenPair = $this->accessTokenEncoder->encode($userId);

        $accessToken->accessToken = $tokenPair->accessToken;
        $accessToken->refreshToken = $tokenPair->refreshToken;
        $accessToken->accessTokenExpiresAt = $tokenPair->accessTokenExpiresAt;
        $accessToken->refreshExpiresAt = $tokenPair->refreshTokenExpiresAt;

        return $accessToken;
    }
}
