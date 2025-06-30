<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services
        ->load(
            'App\\Infrastructure\\Normalizer\\',
            param('kernel.project_dir')->__toString() . '/src/Infrastructure/Normalizer',
        )
        ->tag('controller.service_arguments');
};
