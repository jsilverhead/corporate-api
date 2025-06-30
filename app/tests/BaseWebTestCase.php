<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use SlopeIt\ClockMock\ClockMock;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

abstract class BaseWebTestCase extends WebTestCase
{
    private KernelBrowser $kernelBrowser;

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
            //            $this->getService(AccessTokenBuilder::class), //todo fix
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
