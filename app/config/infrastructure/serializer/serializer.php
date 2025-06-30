<?php

declare(strict_types=1);

use App\Infrastructure\Serializer\JsonSerializer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(JsonSerializer::class)->args([
        '$ensureNoObjects' => true,
        '$sortKeys' => true,
    ]);
};
