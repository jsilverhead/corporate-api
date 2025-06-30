<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (DoctrineConfig $doctrine, ContainerConfigurator $container): void {
    $dbal = $doctrine->dbal();
    $defaultConnectionName = 'default';
    $dbal
        ->connection($defaultConnectionName)
        ->url(env('DATABASE_URL'))
        ->logging(param('kernel.debug'))
        ->schemaFilter('/(?<!_seq)$/')
        ->useSavepoints(true);

    $doctrine->orm()->autoGenerateProxyClasses(param('kernel.debug'));

    $entityManager = $doctrine->orm()->entityManager($defaultConnectionName);
    $entityManager->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware')->autoMapping(true);
    $services = $container->services();

    $services->defaults()->autowire();
};
