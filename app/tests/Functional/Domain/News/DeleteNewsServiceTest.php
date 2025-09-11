<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\News;

use App\Domain\Common\Exception\AccessIsDeniedException;
use App\Domain\News\Repository\NewsRepository;
use App\Domain\News\Service\DeleteNewsService;
use App\Tests\BaseWebTestCase;
use App\Tests\Builder\EmployeeBuilder;
use App\Tests\Builder\NewsBuilder;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 *
 * @coversNothing
 */
#[CoversClass(DeleteNewsService::class)]
final class DeleteNewsServiceTest extends BaseWebTestCase
{
    public function testAccessIsDeniedFail(): void
    {
        $news = $this->getService(NewsBuilder::class)->build();
        $employee = $this->getService(EmployeeBuilder::class)->build();

        $this->expectException(AccessIsDeniedException::class);
        $this->getService(DeleteNewsService::class)->delete(news: $news, remover: $employee);
    }

    public function testAuthorRemoverSuccess(): void
    {
        $news = $this->getService(NewsBuilder::class)->build();

        $this->getService(DeleteNewsService::class)->delete(news: $news, remover: $news->author);

        $this->getService(EntityManagerInterface::class)->flush();

        $removedNews = $this->getService(NewsRepository::class)->getById($news->id);

        self::assertNull($removedNews);
    }

    public function testSuperuserRemoverSuccess(): void
    {
        $news = $this->getService(NewsBuilder::class)->build();
        $superuser = $this->getService(EmployeeBuilder::class)
            ->asSuperUser()
            ->build();

        $this->getService(DeleteNewsService::class)->delete(news: $news, remover: $superuser);

        $this->getService(EntityManagerInterface::class)->flush();

        $removedNews = $this->getService(NewsRepository::class)->getById($news->id);

        self::assertNull($removedNews);
    }
}
