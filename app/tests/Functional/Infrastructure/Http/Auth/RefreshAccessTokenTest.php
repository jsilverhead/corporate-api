<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Auth;

use App\Infrastructure\Http\Common\Action\Auth\RefreshAccessToken;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\AccessTokenBuilder;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(RefreshAccessToken::class)]
final class RefreshAccessTokenTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $user = $this->getService(UserBuilder::class)->build();
        $accessToken = $this->getService(AccessTokenBuilder::class)->build($user);

        $this->httpRequest(method: Request::METHOD_POST, url: '/refreshToken')
            ->withBody([
                'refreshToken' => $accessToken->refreshToken,
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
