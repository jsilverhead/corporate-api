<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\News;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Repository\NewsRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\News\ListNewsDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\News\ListNewsNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/listNews', methods: [Request::METHOD_GET])]
readonly class ListNews
{
    public function __construct(
        private Responder $responder,
        private NewsRepository $newsRepository,
        private ListNewsDenormalizer $listNewsDenormalizer,
        private ListNewsNormalizer $listNewsNormalizer,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $dto = $this->listNewsDenormalizer->denormalize($payload);
        $news = $this->newsRepository->list(
            count: $dto->pagination->count,
            offset: $dto->pagination->offset,
            directionEnum: $dto->orderDirection,
            requester: $employee,
        );

        $normalizedData = $this->listNewsNormalizer->normalize($news);

        return $this->responder->success($normalizedData);
    }
}
