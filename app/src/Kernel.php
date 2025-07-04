<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/services.php');
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/routes.php');
    }
}
