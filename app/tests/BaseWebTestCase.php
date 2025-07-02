<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\Builder\AccessTokenBuilder;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use SlopeIt\ClockMock\ClockMock;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

abstract class BaseWebTestCase extends WebTestCase
{
    private KernelBrowser $kernelBrowser;

    protected static function assertCountOfItemsInResponse(Response $response, int $expectedCount): void
    {
        $items = self::getFieldFromHttpResponse($response, 'items');

        if (null === $items) {
            throw new RuntimeException('Field items int response is not an array');
        }

        if (!\is_array($items)) {
            throw new RuntimeException('Field items int response is not an array');
        }

        self::assertCount(expectedCount: $expectedCount, haystack: $items);
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    protected static function assertMessageIsInQueue(string $transportName, int $amountMessage = 1): void
    {
        /** @psalm-var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.' . $transportName);
        $envelopes = $transport->get();

        self::assertCount(expectedCount: $amountMessage, haystack: $envelopes, message: 'Сообщение не отправлено.');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    protected static function assertMessageSentAndHandled(string $transportName): void
    {
        /** @psalm-var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.' . $transportName);

        /** @psalm-var array<array-key, Envelope<object>> $sentEnvelopes */
        $sentEnvelopes = $transport->get();

        self::assertCount(1, $sentEnvelopes, 'Сообщение не отправлено.');

        $messageBus = self::getContainer()->get(MessageBusInterface::class);

        /** @psalm-var Envelope $sentEnvelope */
        $sentEnvelope = current($sentEnvelopes);

        $stamps = [new ReceivedStamp($transportName)];
        $handledEnvelope = $messageBus->dispatch($sentEnvelope, $stamps);
        $handledStamp = $handledEnvelope->last(HandledStamp::class);

        self::assertNotNull($handledStamp, 'Сообщение не обработано.');
    }

    protected static function getFieldFromHttpResponse(Response $response, string $fieldName): mixed
    {
        if (false === $response->getContent()) {
            throw new RuntimeException('Response body is empty');
        }
        $content = (string) $response->getContent();

        /** @psalm-var array $responseArray */
        $responseArray = json_decode($content, true, 512, \JSON_THROW_ON_ERROR);

        if (!\array_key_exists($fieldName, $responseArray)) {
            throw new RuntimeException('Response body does not contain field ' . $fieldName . '.');
        }

        return $responseArray[$fieldName] ?? null;
    }

    /**
     * @template TService of object
     *
     * @psalm-param class-string<TService> $serviceId
     *
     * @psalm-return TService
     *
     * @throws ServiceNotFoundException
     */
    protected function getService(string $serviceId): object
    {
        /** @psalm-var object|null $service */
        $service = static::getContainer()->get($serviceId);

        if (null === $service) {
            throw new ServiceNotFoundException($serviceId);
        }

        if (!$service instanceof $serviceId) {
            throw new RuntimeException();
        }

        return $service;
    }

    /**
     * @psalm-param Request::METHOD_* $method
     * @psalm-param non-empty-string $url
     */
    protected function httpRequest(string $method, string $url): RequestBuilder
    {
        return new RequestBuilder(
            $this->kernelBrowser,
            $method,
            $url,
            $this->getService(AccessTokenBuilder::class),
            $this->getService(EntityManagerInterface::class),
        );
    }

    protected function setUp(): void
    {
        $this->kernelBrowser = static::createClient([], ['REMOTE_ADDR' => '127.0.0.88']);
    }

    protected function tearDown(): void
    {
        ClockMock::reset();
        parent::tearDown();
    }
}
