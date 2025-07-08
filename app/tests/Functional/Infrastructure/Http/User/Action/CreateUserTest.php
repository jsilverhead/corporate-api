<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\User\Action;

use App\Domain\Common\ValueObject\Email;
use App\Infrastructure\Http\Common\Action\User\CreateUser;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateUser::class)]
final class CreateUserTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superUser = $this->getService(UserBuilder::class)
            ->asSuperUser()
            ->build();

        $email = Email::tryCreateFromString('olego@company.ru');

        $this->httpRequest(method: Request::METHOD_POST, url: '/createUser')
            ->withAuthentication($superUser)
            ->withBody([
                'name' => 'Олегов Олег',
                'email' => $email->email,
                'password' => 'Password123',
                'role' => 'user',
                'birthDate' => null,
                'departmentId' => null,
                'supervisingId' => null,
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
