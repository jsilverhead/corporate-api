<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\User\Action;

use App\Infrastructure\Http\Common\Action\User\ListUsers;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListUsers::class)]
final class ListUsersTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $userBuilder = $this->getService(UserBuilder::class);
        $userBuilder->withName('Цейхович Олег')->build();
        $userBuilder->withName('Никитин Олег')->build();

        $superuser = $userBuilder->asSuperUser()->withName('Нуркова Оксана')->build();

        $userBuilder->asDeleted()->withName('Олегов Семён')->build();

        $response = $this->httpRequest(method: Request::METHOD_GET, url: '/listUsers')
            ->withAuthentication($superuser)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
                'filter' => [
                    'search' => 'олег',
                ],
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
        $this->assertCountOfItemsInResponse(response: $response, expectedCount: 2);
    }
}
