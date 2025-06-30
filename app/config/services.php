<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $defaults = $services->defaults();
    $defaults->autowire();

    $container->import('packages/*.php');

    //    $container->import('domain/*.php');
    $container->import('infrastructure/*.php');
    $container->import('infrastructure/*/*.php');
    $container->import('infrastructure/*/*/*.php');

    switch ($container->env()) {
        case 'test':
            $container->import('packages/dama_doctrine_test.php');

            //            $services //todo
            //                ->load('App\\Tests\\Builder\\', param('kernel.project_dir')->__toString() . '/tests/Builder')
            //                ->public();
            //
            //            $services->load('App\\Tests\\Mock\\', param('kernel.project_dir')->__toString() . '/tests/Mock')->public();
            break;

        default:
            $container->import('packages/sentry.php');

            break;
    }

    // spiks/user-input-processor
    $services
        ->load(
            'Spiks\\UserInputProcessor\\Denormalizer\\',
            param('kernel.project_dir')->__toString() . '/vendor/spiks/user-input-processor/src/Denormalizer',
        )
        ->public();

    $services
        ->load(
            'App\\Infrastructure\\Denormalizer\\',
            param('kernel.project_dir')->__toString() . '/src/Infrastructure/Denormalizer/*.php',
        )
        ->public();
};
