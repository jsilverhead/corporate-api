<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\News;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Repository\NewsRepository;
use App\Domain\News\Service\UpdateNewsService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\News\PublishNewsDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/publishNews', methods: [Request::METHOD_POST])]
readonly class PublishNews
{
    public function __construct(
        private Responder $responder,
        private EntityManagerInterface $entityManager,
        private NewsRepository $newsRepository,
        private PublishNewsDenormalizer $publishNewsDenormalizer,
        private UpdateNewsService $updateNewsService,
    ) {
    }

    public function __invoke(#[AllowedUserRole([RolesEnum::SUPERUSER])] Employee $superuser, Payload $payload): Response
    {
        $id = $this->publishNewsDenormalizer->denormalize($payload);
        $news = $this->newsRepository->getByIdOrFail($id);

        $this->updateNewsService->publish(news: $news, publisher: $superuser);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
