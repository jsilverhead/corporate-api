<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\User\Action;

use App\Domain\Common\ValueObject\Email;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
final class CreateUserTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superUser = $this->getService(UserBuilder::class)
            ->asSuperUser()
            ->build();

        $email = Email::tryCreateFromString('olego@spiks.ru');

        $this->httpRequest(method: Request::METHOD_POST, url: '/createUser')
            ->withAuthentication($superUser)
            ->withBody([
                'name' => 'Олегов Олег',
                'email' => $email->email,
                'password' => 'Spiks123',
                'role' => 'user',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
