<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Department;

use App\Infrastructure\Http\Common\Action\Department\CreateDepartment;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\UserBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateDepartment::class)]
final class CreateDepartmentTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $superuser = $this->getService(UserBuilder::class)
            ->asSuperUser()
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/createDepartment')
            ->withAuthentication($superuser)
            ->withBody([
                'name' => 'Департамент продаж',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
