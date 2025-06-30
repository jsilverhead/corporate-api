<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Health\Action;

use App\Infrastructure\Responder\Responder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', methods: [Request::METHOD_GET])]
readonly class HealthCheck
{
    public function __construct(private string $appVersion, private string $apiVersion, private Responder $responder)
    {
    }

    public function __invoke(): Response
    {
        return $this->responder->health($this->appVersion, $this->apiVersion);
    }
}
