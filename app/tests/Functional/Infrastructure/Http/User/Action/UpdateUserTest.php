<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\User\Action;

use App\Infrastructure\Http\Common\Action\User\UpdateUser;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateUser::class)]
final class UpdateUserTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $userBuilder = $this->getService(UserBuilder::class);

        $user = $userBuilder->build();
        $superuser = $userBuilder->asSuperUser()->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/updateUser')
            ->withAuthentication($superuser)
            ->withBody([
                'userId' => $user->id->toRfc4122(),
                'name' => 'Олег Разумович',
                'role' => 'superuser',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
