<?php

declare(strict_types=1);

namespace App\Tests\Builder;

use App\Domain\Employee\Employee;
use App\Domain\News\News;
use App\Domain\News\Service\CreateNewsService;
use Doctrine\ORM\EntityManagerInterface;

class NewsBuilder
{
    private ?Employee $author = null;

    /** @psalm-var non-empty-string|null $content */
    private ?string $content = null;

    private bool $published = false;

    /** @psalm-var non-empty-string|null $title */
    private ?string $title = null;

    public function __construct(
        private CreateNewsService $createNewsService,
        private EntityManagerInterface $entityManager,
        private EmployeeBuilder $employeeBuilder,
    ) {
    }

    public function asPublished(): self
    {
        $this->published = true;

        return $this;
    }

    public function build(): News
    {
        $news = $this->createNewsService->create(
            author: $this->author ?? $this->employeeBuilder->build(),
            title: $this->title ?? 'Новый регламент отпусков',
            content: $this->content ?? 'Ознакомиться с регламентом можно по ссылке',
        );

        $news->published = $this->published;

        $this->entityManager->flush();

        return $news;
    }

    public function withAuthor(Employee $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @psalm-param non-empty-string $content
     */
    public function withContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
