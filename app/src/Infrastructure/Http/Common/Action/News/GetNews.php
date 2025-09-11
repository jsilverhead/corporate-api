<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Common\Action\News;

use App\Domain\Employee\Employee;
use App\Domain\Employee\Enum\RolesEnum;
use App\Domain\News\Repository\NewsRepository;
use App\Infrastructure\Attribute\AllowedUserRole;
use App\Infrastructure\Http\Common\Denormalizer\News\GetNewsDenormalizer;
use App\Infrastructure\Http\Common\Normalizer\News\GetNewsNormalizer;
use App\Infrastructure\Payload\Payload;
use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/getNews', methods: [Request::METHOD_GET])]
readonly class GetNews
{
    public function __construct(
        private Responder $responder,
        private NewsRepository $newsRepository,
        private GetNewsDenormalizer $getNewsDenormalizer,
        private GetNewsNormalizer $getNewsNormalizer,
    ) {
    }

    public function __invoke(
        #[AllowedUserRole([RolesEnum::USER, RolesEnum::SUPERUSER])] Employee $employee,
        Payload $payload,
    ): Response {
        $id = $this->getNewsDenormalizer->denormalize($payload);
        $news = $this->newsRepository->getByIdWithAuthorOrFail(id: $id, employee: $employee);
        $normalizedData = $this->getNewsNormalizer->normalize($news);

        return $this->responder->success($normalizedData);
    }
}
