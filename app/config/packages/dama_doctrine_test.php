<?php

declare(strict_types=1);

use Symfony\Config\DamaDoctrineTestConfig;

return static function (DamaDoctrineTestConfig $damaDoctrineTest): void {
    $damaDoctrineTest->enableStaticConnection(true)->enableStaticMetaDataCache(true)->enableStaticQueryCache(true);
};
