<?php

declare(strict_types=1);

use App\Domain\User\User;
use App\Infrastructure\Auth\BearerAuthenticator;
use App\Infrastructure\Auth\UserResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\SecurityConfig;

return static function (ContainerConfigurator $container, SecurityConfig $security): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(BearerAuthenticator::class);

    $services
        ->set(UserResolver::class)
        ->tag('controller.argument_value_resolver', ['priority' => 1])
        ->autowire();

    $security
        ->passwordHasher(User::class)
        ->algorithm('bcrypt')
        ->cost(10);

    $security
        ->firewall('main')
        ->lazy(true)
        ->pattern('^/')
        ->customAuthenticators([BearerAuthenticator::class]);
};
