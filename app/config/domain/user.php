<?php

declare(strict_types=1);

use App\Domain\User\Service\CreateUserService;
use App\Domain\User\Service\DeleteUserService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $entityManager = $doctrine->orm()->entityManager('default');
    $entityManager
        ->mapping('App\\Domain\\User')
        ->type('attribute')
        ->prefix('App\Domain\User')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/User');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\\Domain\\User\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/User/Service',
    );

    $services->load(
        'App\\Domain\\User\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/User/Repository',
    );

    $services->set(CreateUserService::class)->public();
    $services->set(DeleteUserService::class)->public();
};
