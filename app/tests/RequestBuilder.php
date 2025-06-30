<?php

declare(strict_types=1);

namespace App\Tests;

// use App\Tests\Builder\AccessTokenBuilder;
// use App\Domain\User\User;
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
        //        private readonly AccessTokenBuilder $accessTokenBuilder,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     */
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

    /**
     * @psalm-param list<mixed> $data
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function withArguments(array $data): self
    {
        $this->content['arguments'] = $data;

        return $this;
    }

    //    public function withAuthentication(User $user): self
    //    {
    //        $this->content['accessToken'] = $this->accessTokenBuilder->build($user)->accessToken;
    //
    //        return $this;
    //    }

    /**
     * @psalm-param list<mixed> $data
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function withContent(array $data): self
    {
        $this->content = $data;

        return $this;
    }
}
