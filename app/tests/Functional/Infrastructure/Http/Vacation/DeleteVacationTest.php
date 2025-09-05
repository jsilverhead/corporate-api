<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Vacation;

use App\Infrastructure\Http\Common\Action\Vacation\DeleteVacation;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\VacationBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteVacation::class)]
final class DeleteVacationTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $vacation = $this->getService(VacationBuilder::class)
            ->withEmployee($employee)
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/deleteVacation')
            ->withAuthentication($employee)
            ->withBody([
                'id' => $vacation->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
