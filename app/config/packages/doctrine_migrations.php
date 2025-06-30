<?php

declare(strict_types=1);

use Symfony\Config\DoctrineMigrationsConfig;

return static function (DoctrineMigrationsConfig $containerConfigurator): void {
    $containerConfigurator->migrationsPath('DoctrineMigrations', '%kernel.project_dir%/migrations');
};
