<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\RateLimiter\Storage\CacheStorage;
use Symfony\Component\RateLimiter\Storage\StorageInterface;
use Symfony\Config\FrameworkConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container, FrameworkConfig $framework): void {
    $framework->httpMethodOverride(false);
    $framework->defaultLocale('ru');

    $framework->router()->strictRequirements(param('kernel.debug')->__toString())->utf8(true);

    $framework->test('test' === $container->env());

    $container
        ->services()
        ->set(StorageInterface::class, CacheStorage::class)
        ->args([service('cache.app')]);

    $framework
        ->cache()
        ->defaultDoctrineDbalProvider('doctrine.dbal.default_connection')
        ->app('cache.adapter.doctrine_dbal');
};
