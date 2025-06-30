<?php

declare(strict_types=1);

use Symfony\Config\SentryConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

return static function (SentryConfig $sentry): void {
    $sentry->dsn(env('SENTRY_DSN'));
    $sentry->options()->environment(env('ENVIRONMENT_NAME'))->release(env('APP_VERSION'))->sendDefaultPii(true);
};
