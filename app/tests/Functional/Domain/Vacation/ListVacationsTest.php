<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Vacation;

use App\Domain\Common\ValueObject\Period;
use App\Infrastructure\Http\Common\Action\Vacation\ListVacations;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\DepartmentBuilder;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\VacationBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(ListVacations::class)]
final class ListVacationsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $departmentBuilder = $this->getService(DepartmentBuilder::class);
        $backendDepartment = $departmentBuilder->withName('Backend')->build();
        $salesDepartment = $departmentBuilder->withName('Продажи')->build();

        $employeeBuilder = $this->getService(EmployeeBuilder::class);
        $employee = $employeeBuilder->withDepartment($salesDepartment)->build();
        $employeeBuilder->withDepartment($backendDepartment)->build();
        $employeeBuilder->withDepartment($salesDepartment)->build();

        $period = new Period(fromDate: new DateTimeImmutable('+1 day'), toDate: new DateTimeImmutable('+8 days'));

        $this->getService(VacationBuilder::class)
            ->withEmployee($employee)
            ->withPeriod($period)
            ->asApproved()
            ->build();

        $this->httpRequest(method: Request::METHOD_GET, url: 'listVacations')
            ->withAuthentication($employee)
            ->withQuery([
                'pagination' => [
                    'count' => 10,
                    'offset' => 0,
                ],
                'period' => [
                    'fromDate' => (new DateTimeImmutable())->format('c'),
                    'toDate' => (new DateTimeImmutable('+30 days'))->format('c'),
                ],
                'status' => 'all',
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
