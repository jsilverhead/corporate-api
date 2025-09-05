<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Vacation;

use App\Infrastructure\Http\Common\Action\Vacation\UpdateVacation;
use App\Tests\BaseWebTestCase;
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
#[CoversClass(UpdateVacation::class)]
final class UpdateVacationTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $vacation = $this->getService(VacationBuilder::class)
            ->withEmployee($employee)
            ->build();
        $fromDate = new DateTimeImmutable('+15 days');
        $toDate = new DateTimeImmutable('+20 days');

        $this->httpRequest(method: Request::METHOD_POST, url: '/updateVacation')
            ->withAuthentication($employee)
            ->withBody([
                'id' => $vacation->id->toRfc4122(),
                'period' => [
                    'fromDate' => $fromDate->format('c'),
                    'toDate' => $toDate->format('c'),
                ],
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
