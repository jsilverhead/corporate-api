<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\User\Action;

use App\Infrastructure\Http\Common\Action\User\GetUser;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(GetUser::class)]
final class GetUserTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $userBuilder = $this->getService(UserBuilder::class);
        $user = $userBuilder->withBirthDate(new DateTimeImmutable('2000-01-02'))->build();
        $superuser = $userBuilder->asSuperUser()->build();

        $this->httpRequest(method: Request::METHOD_GET, url: '/getUser')
            ->withAuthentication($superuser)
            ->withQuery(['id' => $user->id->toRfc4122()])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
