<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return static function (ContainerInterface $container): App {
    /** @var Slim\App<Psr\Container\ContainerInterface|null> $app */
    $app = AppFactory::createFromContainer($container);
    (require __DIR__ . '/middleware.php')($app, $container);
    (require __DIR__ . '/routes.php')($app);
    return $app;
};
