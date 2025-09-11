<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\News;

use App\Domain\Common\Exception\AccessIsDeniedException;
use App\Domain\News\Exception\NewsIsAlreadyPublishedException;
use App\Domain\News\Service\UpdateNewsService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\NewsBuilder;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(UpdateNewsService::class)]
final class UpdateNewsServiceTest extends BaseWebTestCase
{
    public function testAlreadyPublishedNewsFail(): void
    {
        $news = $this->getService(NewsBuilder::class)
            ->asPublished()
            ->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $this->expectException(NewsIsAlreadyPublishedException::class);
        $this->getService(UpdateNewsService::class)->publish(news: $news, publisher: $superuser);
    }

    public function testPublishNewsNotSuperUserFail(): void
    {
        $publisher = $this->getService(EmployeeBuilder::class)->build();
        $news = $this->getService(NewsBuilder::class)->build();

        $this->expectException(AccessIsDeniedException::class);
        $this->getService(UpdateNewsService::class)->publish(news: $news, publisher: $publisher);
    }

    public function testPublishNewsSuccess(): void
    {
        $news = $this->getService(NewsBuilder::class)->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $this->getService(UpdateNewsService::class)->publish(news: $news, publisher: $superuser);

        self::assertTrue($news->published);
    }

    public function testUpdateNewsNotAuthorFail(): void
    {
        $editor = $this->getService(EmployeeBuilder::class)->build();
        $news = $this->getService(NewsBuilder::class)->build();

        $this->expectException(AccessIsDeniedException::class);
        $this->getService(UpdateNewsService::class)->update(
            news: $news,
            title: 'Выход нового сотрудника',
            content: 'Добро пожаловать в нашу команду',
            editor: $editor,
        );
    }

    public function testUpdateNewsSuccess(): void
    {
        $author = $this->getService(EmployeeBuilder::class)->build();
        $news = $this->getService(NewsBuilder::class)
            ->withAuthor($author)
            ->build();
        $newTitle = 'Выход нового сотрудника';
        $newContent = 'Добро пожаловать в нашу команду';

        $this->getService(UpdateNewsService::class)->update(
            news: $news,
            title: $newTitle,
            content: $newContent,
            editor: $author,
        );

        self::assertSame(expected: $newTitle, actual: $news->title);
        self::assertSame(expected: $newContent, actual: $news->content);
    }
}
