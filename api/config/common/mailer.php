<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

return [
  Mailer::class => function (ContainerInterface $container) {
      /**
       * @psalm-suppress MixedArrayAccess
       * @psalm-var array{
       *     host:string,
       *     port:int,
       *     user:string,
       *     password:string,
       *     encryption:string,
       * } $config
       */
    $config = $container->get('config')['mailer'];

    $transport = (new EsmtpTransport($config['host'], $config['port']))
          ->setUsername($config['user'])
          ->setPassword($config['password']);

    return new Mailer($transport);
  },
    'config' => [
        'mailer' => [
            'host' => getenv('MAILER_HOST'),
            'port' => getenv('MAILER_PORT'),
            'user' => getenv('MAILER_USER'),
            'password' => getenv('MAILER_PASSWORD'),
            'encryption' => getenv('MAILER_ENCRYPTION'),
            'from' => [getenv('MAILER_FROM_EMAIL') => 'Auction'],
        ]
    ]
];