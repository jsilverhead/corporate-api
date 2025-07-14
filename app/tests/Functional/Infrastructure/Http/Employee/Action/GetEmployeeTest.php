<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Employee\Action;

use App\Infrastructure\Http\Common\Action\Employee\GetEmployee;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(GetEmployee::class)]
final class GetEmployeeTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $userBuilder = $this->getService(EmployeeBuilder::class);
        $employee = $userBuilder->withBirthDate(new DateTimeImmutable('2000-01-02'))->build();
        $superuser = $userBuilder->asSuperUser()->build();

        $this->httpRequest(method: Request::METHOD_GET, url: '/getEmployee')
            ->withAuthentication($superuser)
            ->withQuery(['id' => $employee->id->toRfc4122()])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
