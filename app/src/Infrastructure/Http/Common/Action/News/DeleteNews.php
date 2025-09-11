<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\News;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Repository\NewsRepository;
use App\Domain\News\Service\DeleteNewsService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\News\DeleteNewsDenormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/deleteNews', methods: [Request::METHOD_POST])]
readonly class DeleteNews
{
    public function __construct(
        private Responder $responder,
        private DeleteNewsService $deleteNewsService,
        private DeleteNewsDenormalizer $deleteNewsDenormalizer,
        private NewsRepository $newsRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $id = $this->deleteNewsDenormalizer->denormalize($payload);
        $news = $this->newsRepository->getByIdOrFail($id);

        $this->deleteNewsService->delete(news: $news, remover: $employee);

        $this->entityManager->flush();

        return $this->responder->success();
    }
}
