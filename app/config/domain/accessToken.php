<?php

declare(strict_types=1);

use App\Domain\AccessToken\JwtAuthSettings;
use App\Domain\AccessToken\Service\AccessTokenEncoder;
use App\Domain\AccessToken\Service\CreateAccessTokenService;
use App\Domain\AccessToken\Service\RefreshAccessTokenService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $entityManager = $doctrine->orm()->entityManager('default');
    $entityManager
        ->mapping('App\\Domain\\AccessToken')
        ->type('attribute')
        ->prefix('App\Domain\AccessToken')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/AccessToken');

    $services->load(
        'App\\Domain\\AccessToken\\Repository\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/AccessToken/Repository',
    );

    $services->load(
        'App\\Domain\\AccessToken\\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/AccessToken/Service',
    );

    $services->set(AccessTokenEncoder::class)->public();
    $services->set(CreateAccessTokenService::class)->public();
    $services->set(RefreshAccessTokenService::class)->public();

    $services
        ->set(JwtAuthSettings::class)
        ->args([
            '$secret' => env('AUTH_JWT_SECRET')->string(),
            '$algorithm' => env('AUTH_JWT_ALGORITHM')->string(),
            '$accessTokenTimeOfLife' => env('AUTH_JWT_ACCESS_TIME_OF_LIFE')->string(),
            '$refreshTokenTimeOfLife' => env('AUTH_JWT_REFRESH_TIME_OF_LIFE')->string(),
        ])
        ->public();
};
