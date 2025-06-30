<?php

declare(strict_types=1);

use App\Infrastructure\Responder\Responder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(Responder::class);
};
