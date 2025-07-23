<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $entityManager = $doctrine->orm()->entityManager('default');
    $entityManager
        ->mapping('App\\Domain\\Vacation')
        ->type('attribute')
        ->prefix('App\Domain\Vacation')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Vacation');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\\Domain\\Vacation\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Vacation/Service',
    );

    $services->load(
        'App\\Domain\\Vacation\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Vacation/Repository',
    );
};
