<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services
        ->load(
            'App\\Infrastructure\\Http\\Common\\Action\\',
            param('kernel.project_dir')->__toString() . '/src/Infrastructure/Http/Common/Action',
        )
        ->tag('controller.service_arguments');

    $services->load(
        'App\\Infrastructure\\Http\\Common\\Normalizer\\',
        param('kernel.project_dir')->__toString() . '/src/Infrastructure/Http/Common/Normalizer',
    );

    $services->load(
        'App\\Infrastructure\\Http\\Common\\Denormalizer\\',
        param('kernel.project_dir')->__toString() . '/src/Infrastructure/Http/Common/Denormalizer',
    );
};
