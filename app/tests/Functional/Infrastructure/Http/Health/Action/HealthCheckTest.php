<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\Health\Action;

use App\Infrastructure\Http\Health\Action\HealthCheck;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @covers \App\Infrastructure\Http\Health\Action\HealthCheck
 */
#[CoversClass(HealthCheck::class)]
final class HealthCheckTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $this->httpRequest(Request::METHOD_GET, '/')->execute();

        self::assertResponseStatusCodeSame(200);
    }
}
