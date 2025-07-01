<?php

declare(strict_types=1);

use App\Infrastructure\Payload\PayloadDeserializer;
use App\Infrastructure\Payload\PayloadResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(PayloadDeserializer::class);

    $services
        ->set(PayloadResolver::class)
        ->tag('controller.argument_value_resolver', ['priority' => 2])
        ->autoconfigure()
        ->autowire();
};
