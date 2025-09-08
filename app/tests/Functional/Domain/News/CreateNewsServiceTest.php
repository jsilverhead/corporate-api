<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\News;

use App\Domain\News\News;
use App\Domain\News\Service\CreateNewsService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(CreateNewsService::class)]
final class CreateNewsServiceTest extends BaseWebTestCase
{
    public function testSuperuserAuthorSuccess(): void
    {
        $author = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();
        $title = 'Выход нового сотрудника';
        $content = 'Аналитик Иванов Иван, добро пожаловать в нашу команду...';

        $news = $this->getService(CreateNewsService::class)->create(author: $author, title: $title, content: $content);

        self::assertInstanceOf(expected: News::class, actual: $news);
        self::assertSame(expected: $title, actual: $news->title);
        self::assertSame(expected: $content, actual: $news->content);
        self::assertTrue($news->author->id->equals($author->id));
        self::assertTrue($news->published);
    }

    public function testUserAuthorSuccess(): void
    {
        $author = $this->getService(EmployeeBuilder::class)->build();
        $title = 'Выход нового сотрудника';
        $content = 'Аналитик Иванов Иван, добро пожаловать в нашу команду...';

        $news = $this->getService(CreateNewsService::class)->create(author: $author, title: $title, content: $content);

        self::assertFalse($news->published);
    }
}
