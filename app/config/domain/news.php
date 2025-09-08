<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\\Domain\\News\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/News/Service',
    );

    $services->load(
        'App\\Domain\\News\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/News/Repository',
    );

    $entityManager = $doctrine->orm()->entityManager('default');
    $entityManager
        ->mapping('App\\Domain\\News\\')
        ->type('attribute')
        ->prefix('App\Domain\News')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/News');

    $services->set(App\Domain\News\Service\CreateNewsService::class)->public();
};
