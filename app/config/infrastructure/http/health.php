<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire()->bind('$appVersion', env('APP_VERSION'))->bind('$apiVersion', env('API_VERSION'));

    $services
        ->load(
            'App\\Infrastructure\\Http\\Health\\Action\\',
            param('kernel.project_dir')->__toString() . '/src/Infrastructure/Http/Health/Action',
        )
        ->tag('controller.service_arguments');
};
