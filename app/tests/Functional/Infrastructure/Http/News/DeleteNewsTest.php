<?php

declare(strict_types=1);

namespace App\Tests\Functional\Infrastructure\Http\News;

use App\Infrastructure\Http\Common\Action\News\DeleteNews;
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
#[CoversClass(DeleteNews::class)]
final class DeleteNewsTest extends BaseWebTestCase
{
    public function testSuccess(): void
    {
        $author = $this->getService(EmployeeBuilder::class)->build();
        $news = $this->getService(NewsBuilder::class)
            ->withAuthor($author)
            ->build();

        $this->httpRequest(method: Request::METHOD_POST, url: '/deleteNews')
            ->withAuthentication($author)
            ->withBody([
                'id' => $news->id->toRfc4122(),
            ])
            ->execute();

        $this->assertResponseStatusCodeSame(200);
    }
}
