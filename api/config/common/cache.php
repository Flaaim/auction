<?php


declare(strict_types=1);

use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

return [
  CacheItemPoolInterface::class => function (ContainerInterface $container): CacheItemPoolInterface
  {
      return new FilesystemAdapter('', 0, __DIR__ . '/../../var/cache/');

  }
];