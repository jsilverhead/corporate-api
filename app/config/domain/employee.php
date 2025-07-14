<?php

declare(strict_types=1);

use App\Domain\Employee\Service\CreateEmployeeService;
use App\Domain\Employee\Service\DeleteEmployeeService;
use App\Domain\Employee\Service\UpdateEmployeeService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $entityManager = $doctrine->orm()->entityManager('default');
    $entityManager
        ->mapping('App\\Domain\\Employee')
        ->type('attribute')
        ->prefix('App\Domain\Employee')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Employee');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\\Domain\\Employee\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Employee/Service',
    );

    $services->load(
        'App\\Domain\\Employee\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Employee/Repository',
    );

    $services->set(CreateEmployeeService::class)->public();
    $services->set(DeleteEmployeeService::class)->public();
    $services->set(UpdateEmployeeService::class)->public();
};
