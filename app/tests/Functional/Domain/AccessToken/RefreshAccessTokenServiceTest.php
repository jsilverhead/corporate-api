<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\AccessToken;

use App\Domain\AccessToken\Exception\UnknownTokenException;
use App\Domain\AccessToken\JwtAuthSettings;
use App\Domain\AccessToken\Service\RefreshAccessTokenService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\AccessTokenBuilder;
use App\Tests\Builder\EmployeeBuilder;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use PHPUnit\Framework\Attributes\CoversClass;
use SlopeIt\ClockMock\ClockMock;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(RefreshAccessTokenService::class)]
final class RefreshAccessTokenServiceTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $user = $this->getService(EmployeeBuilder::class)->build();
        $now = new DateTimeImmutable();

        ClockMock::freeze($now->modify('-1 day'));
        $accessToken = $this->getService(AccessTokenBuilder::class)->build($user);
        $oldAccessToken = $accessToken->accessToken;
        $oldRefreshToken = $accessToken->refreshToken;
        ClockMock::reset();

        ClockMock::freeze($now);
        $newAccessToken = $this->getService(RefreshAccessTokenService::class)->refresh($accessToken->refreshToken);
        ClockMock::reset();

        $settings = $this->getService(JwtAuthSettings::class);

        /**
         * @psalm-var DateTimeImmutable $newAccessTokenExpiresAt
         */
        $newAccessTokenExpiresAt = $now->modify(sprintf('+%d seconds', $settings->accessTokenTimeOfLife));

        /**
         * @psalm-var DateTimeImmutable $newRefreshTokenExpiresAt
         */
        $newRefreshTokenExpiresAt = $now->modify(sprintf('+%d seconds', $settings->refreshTokenTimeOfLife));

        self::assertNotSame(expected: $oldAccessToken, actual: $newAccessToken->accessToken);
        self::assertNotSame(expected: $oldRefreshToken, actual: $newAccessToken->refreshToken);
        self::assertSame(
            expected: $newAccessToken->accessTokenExpiresAt->getTimestamp(),
            actual: $newAccessTokenExpiresAt->getTimestamp(),
        );
        self::assertSame(
            expected: $newAccessToken->refreshExpiresAt->getTimestamp(),
            actual: $newRefreshTokenExpiresAt->getTimestamp(),
        );
    }

    public function testUnknownTokenFail(): void
    {
        $expiresAt = (new DateTimeImmutable())->modify('+1 day');

        $payload = [
            'exp' => $expiresAt->getTimestamp(),
            'userId' => Uuid::v4(),
        ];

        $settings = $this->getService(JwtAuthSettings::class);

        /**
         * @psalm-var non-empty-string $refreshToken
         */
        $refreshToken = JWT::encode(payload: $payload, key: $settings->secret, alg: $settings->algorithm);

        $this->expectException(UnknownTokenException::class);
        $this->getService(RefreshAccessTokenService::class)->refresh($refreshToken);
    }
}
