<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->import('../src/Infrastructure/Http/*/Action/*', 'attribute');
    $routes->import('../src/Infrastructure/Http/*/Action/*/*', 'attribute');
    $routes->import('../src/Infrastructure/Http/*/Action/*/*/*', 'attribute');
};
