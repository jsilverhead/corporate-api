<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Employee\Action;

use App\Domain\Common\ValueObject\Email;
use App\Infrastructure\Http\Common\Action\Employee\CreateEmployee;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateEmployee::class)]
final class CreateEmployeeTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superUser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $email = Email::tryCreateFromString('olego@company.ru');

        $this->httpRequest(method: Request::METHOD_POST, url: '/createEmployee')
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
