<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\Employee\Employee;
use App\Tests\Builder\AccessTokenBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 */
final class RequestBuilder
{
    private array $content = [];

    /** @psalm-var mixed[] */
    private array $parameters = [];

    private string $query = '';

    /** @psalm-var string[] */
    private array $server = [];

    public function __construct(
        private readonly KernelBrowser $client,
        private readonly string $method,
        private readonly string $url,
        private readonly AccessTokenBuilder $accessTokenBuilder,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function execute(): Response
    {
        $this->entityManager->clear();

        $this->server['HTTP_CONTENT-TYPE'] = 'application/json';
        $this->server['CONTENT_TYPE'] = 'application/json';

        $this->client->request(
            $this->method,
            $this->url . ('' === $this->query ? '' : '?' . $this->query),
            $this->parameters,
            [],
            $this->server,
            json_encode($this->content, flags: \JSON_THROW_ON_ERROR),
        );

        return $this->client->getResponse();
    }

    public function withAuthentication(Employee $user): self
    {
        $this->server['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->accessTokenBuilder->build($user)->accessToken;

        return $this;
    }

    public function withBody(array $data): self
    {
        $this->content = $data;
        $this->server['HTTP_CONTENT-TYPE'] = 'application/json';

        return $this;
    }

    public function withQuery(array $query): self
    {
        $this->query = http_build_query($query);

        return $this;
    }
}
