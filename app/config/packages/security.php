<?php

declare(strict_types=1);

use App\Domain\User\User;
use Symfony\Bundle\SecurityBundle\Security\UserAuthenticator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\SecurityConfig;

return static function (ContainerConfigurator $container, SecurityConfig $security): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(UserAuthenticator::class);

    $security
        ->passwordHasher(User::class)
        ->algorithm('bcrypt')
        ->cost(10);

    $security->firewall('main')->lazy(true)->pattern('^/');
};
