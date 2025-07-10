<?php

declare(strict_types=1);

use App\Domain\Department\Service\AddEmployeeService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $entityManager = $doctrine->orm()->entityManager('default');
    $entityManager
        ->mapping('App\\Domain\\Department')
        ->type('attribute')
        ->prefix('App\Domain\Department')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Department');

    $services->load(
        'App\\Domain\\Department\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Department/Service',
    );

    $services->load(
        'App\\Domain\\Department\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Department/Repository',
    );
};
