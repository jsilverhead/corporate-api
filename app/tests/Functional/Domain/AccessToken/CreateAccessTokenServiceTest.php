<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\AccessToken;

use App\Domain\AccessToken\JwtAuthSettings;
use App\Domain\AccessToken\Repository\AccessTokenRepository;
use App\Domain\AccessToken\Service\AccessTokenEncoder;
use App\Domain\AccessToken\Service\CreateAccessTokenService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @internal
 *
 * @coversNothing
 */
final class CreateAccessTokenServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $user = $this->getService(UserBuilder::class)->build();

        $secret = 'corporateSecret';
        $algorithm = 'HS256';
        $accessTimeOfLife = 3600;
        $refreshTimeOfLife = 3600;

        $jwtAuthSettings = new JwtAuthSettings(
            secret: $secret,
            algorithm: $algorithm,
            accessTokenTimeOfLife: $accessTimeOfLife,
            refreshTokenTimeOfLife: $refreshTimeOfLife,
        );

        $accessTokenEncoder = new AccessTokenEncoder($jwtAuthSettings);

        $service = new CreateAccessTokenService(
            accessTokenEncoder: $accessTokenEncoder,
            accessTokenRepository: $this->getService(AccessTokenRepository::class),
        );

        $accessToken = $service->create($user);

        $decodedAccessToken = JWT::decode(
            jwt: $accessToken->accessToken,
            keyOrKeyArray: new Key(keyMaterial: $secret, algorithm: $algorithm),
        );

        $userId = $decodedAccessToken->userId;

        self::assertSame(expected: $user->id->toRfc4122(), actual: $userId);
    }
}
