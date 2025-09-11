<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\News;

use App\Infrastructure\Http\Common\Action\News\GetNews;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\NewsBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(GetNews::class)]
final class GetNewsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $news = $this->getService(NewsBuilder::class)
            ->asPublished()
            ->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->httpRequest(method: Request::METHOD_GET, url: '/getNews')
            ->withAuthentication($employee)
            ->withQuery([
                'id' => $news->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
