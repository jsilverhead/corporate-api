<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Auth;

use App\Domain\AccessToken\Exception\ExpiredAccessTokenException;
use App\Domain\AccessToken\Exception\UnknownTokenException;
use App\Domain\AccessToken\JwtAuthSettings;
use App\Domain\Common\Exception\Jwt\JwtTokenIsInvalidException;
use App\Domain\User\User;
use App\Infrastructure\Auth\BearerAuthenticator;
use App\Infrastructure\Auth\Exception\AuthorizationHeaderMissingException;
use App\Infrastructure\Payload\Exception\InvalidAuthorizationHeaderException;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\AccessTokenBuilder;
use App\Tests\Builder\UserBuilder;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use SlopeIt\ClockMock\ClockMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(BearerAuthenticator::class)]
final class BearerAuthenticatorTest extends BaseWebTestCase
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function invalidAuthorizationToken(): array
    {
        return [
            ['Bearer qwe zxc'],
            ['Bearer '],
            ['qwe'],
            [
                'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c',
            ],
        ];
    }

    public function testAuthHeaderIsMissingFail(): void
    {
        $request = new Request();

        $this->expectException(AuthorizationHeaderMissingException::class);

        $this->getService(BearerAuthenticator::class)->authenticate($request);
    }

    public function testExpiredAccessTokenFail(): void
    {
        $user = $this->getService(UserBuilder::class)->build();

        ClockMock::freeze(new DateTimeImmutable('-1 month'));
        $accessToken = $this->getService(AccessTokenBuilder::class)->build($user);
        ClockMock::reset();

        $request = new Request(server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $accessToken->accessToken]);

        $this->expectException(ExpiredAccessTokenException::class);

        $this->getService(BearerAuthenticator::class)->authenticate($request);
    }

    #[DataProvider('invalidAuthorizationToken')]
    public function testInvalidAuthorizationHeaderFail(string $token): void
    {
        $request = new Request(server: ['HTTP_AUTHORIZATION' => $token]);

        $this->expectException(InvalidAuthorizationHeaderException::class);

        $this->getService(BearerAuthenticator::class)->authenticate($request);
    }

    public function testInvalidJwtFail(): void
    {
        $request = new Request(server: ['HTTP_AUTHORIZATION' => 'Bearer qwe']);

        $this->expectException(JwtTokenIsInvalidException::class);

        $this->getService(BearerAuthenticator::class)->authenticate($request);
    }

    public function testSuccess(): void
    {
        $user = $this->getService(UserBuilder::class)->build();
        $accessToken = $this->getService(AccessTokenBuilder::class)->build($user);

        $request = new Request(server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $accessToken->accessToken]);

        $passport = $this->getService(BearerAuthenticator::class)->authenticate($request);

        self::assertInstanceOf(expected: User::class, actual: $passport->getUser());
        self::assertSame(expected: $user->id->toRfc4122(), actual: $passport->getUser()->getUserIdentifier());
    }

    public function testTokenIsNotLinkedToUserFail(): void
    {
        $jwtSettings = $this->getService(JwtAuthSettings::class);
        $fakeJwt = JWT::encode(['userId' => (string) Uuid::v4()], $jwtSettings->secret, $jwtSettings->algorithm);

        $request = new Request(server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $fakeJwt]);

        $this->expectException(UnknownTokenException::class);

        $this->getService(BearerAuthenticator::class)->authenticate($request);
    }
}
