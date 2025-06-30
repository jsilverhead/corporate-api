<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\MonologConfig;

return static function (MonologConfig $monolog, ContainerConfigurator $container): void {
    $monolog
        ->handler('file_log')
        ->type('rotating_file')
        ->path('%kernel.logs_dir%/%kernel.environment%.log')
        ->level('debug')
        ->formatter('monolog.formatter.line')
        ->maxFiles(10);

    //    $monolog
    //        ->handler('sentry_breadcrumbs')
    //        ->type('service')
    //        ->id(SentryBreadcrumbsHandler::class)
    //        ->level('debug')
    //        ->channels()
    //        ->elements(['!event']);

    $monolog
        ->handler('stdout')
        ->type('stream')
        ->path('php://stdout')
        ->level('debug')
        ->formatter('monolog.formatter.line')
        ->channels()
        ->elements(['!event']);
};
