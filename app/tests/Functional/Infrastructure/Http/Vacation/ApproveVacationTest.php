<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Vacation;

use App\Infrastructure\Http\Common\Action\Vacation\ApproveVacation;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\VacationBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ApproveVacation::class)]
final class ApproveVacationTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $department = $this->getService(DepartmentBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)
            ->withDepartment($department)
            ->build();
        $supervisor = $this->getService(EmployeeBuilder::class)
            ->withSupervising($department)
            ->build();
        $vacation = $this->getService(VacationBuilder::class)
            ->withEmployee($employee)
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/approveVacation')
            ->withAuthentication($supervisor)
            ->withBody([
                'id' => $vacation->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
