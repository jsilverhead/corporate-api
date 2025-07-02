<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Auth;

use App\Infrastructure\Http\Common\Action\Auth\IssueAccessTokenByEmailAndPassword;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(IssueAccessTokenByEmailAndPassword::class)]
final class IssueAccessTokenByEmailAndPasswordTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $password = 'Password12345';
        $user = $this->getService(UserBuilder::class)
            ->withPassword($password)
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/issueTokenByEmailAndPassword')
            ->withBody([
                'email' => $user->email->email,
                'password' => $password,
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
