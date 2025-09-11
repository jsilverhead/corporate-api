<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\News;

use App\Infrastructure\Http\Common\Action\News\PublishNews;
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
#[CoversClass(PublishNews::class)]
final class PublishNewsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $news = $this->getService(NewsBuilder::class)->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/publishNews')
            ->withAuthentication($superuser)
            ->withBody([
                'id' => $news->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
