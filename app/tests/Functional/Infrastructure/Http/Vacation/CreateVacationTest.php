<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Vacation;

use App\Infrastructure\Http\Common\Action\Vacation\CreateVacation;
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
#[CoversClass(CreateVacation::class)]
final class CreateVacationTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $employee = $this->getService(EmployeeBuilder::class)->build();
        $now = new DateTimeImmutable();
        $fromDate = $now->modify('+1 day');
        $toDate = $fromDate->modify('+8 days');

        $this->httpRequest(method: Request::METHOD_POST, url: '/createVacation')
            ->withAuthentication($employee)
            ->withBody([
                'period' => [
                    'fromDate' => $fromDate->format('c'),
                    'toDate' => $toDate->format('c'),
                ],
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
