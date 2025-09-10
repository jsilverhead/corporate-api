<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\News;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Service\CreateNewsService;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\News\AddNewsDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\News\AddNewsNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/addNews', methods: [Request::METHOD_POST])]
readonly class AddNews
{
    public function __construct(
        private Responder $responder,
        private CreateNewsService $createNewsService,
        private EntityManagerInterface $entityManager,
        private AddNewsDenormalizer $addNewsDenormalizer,
        private AddNewsNormalizer $addNewsNormalizer,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->addNewsDenormalizer->denormalize($payload);

        $news = $this->createNewsService->create(author: $employee, title: $dto->title, content: $dto->content);

        $this->entityManager->flush();

        $normalizedData = $this->addNewsNormalizer->normalize($news);

        return $this->responder->success($normalizedData);
    }
}
