<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\News;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Repository\NewsRepository;
use App\Domain\News\Service\UpdateNewsService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\News\UpdateNewsDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/updateNews', methods: [Request::METHOD_POST])]
readonly class UpdateNews
{
    public function __construct(
        private Responder $responder,
        private EntityManagerInterface $entityManager,
        private UpdateNewsService $updateNewsService,
        private NewsRepository $newsRepository,
        private UpdateNewsDenormalizer $updateNewsDenormalizer,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->updateNewsDenormalizer->denormalize($payload);
        $news = $this->newsRepository->getByIdOrFail($dto->id);

        $this->updateNewsService->update(news: $news, title: $dto->title, content: $dto->content, editor: $employee);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
