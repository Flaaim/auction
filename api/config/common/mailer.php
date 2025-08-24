<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\EventListener\EnvelopeListener;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;

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
       *     from:array{email:string, name:string}
       * } $config
       */
    $config = $container->get('config')['mailer'];
    $dispatcher = new EventDispatcher();

    $dispatcher->addSubscriber(
      new EnvelopeListener(
          new Address(
              $config['from']['email'],
              $config['from']['name']
          ),
      ),
    );

    $transport = (new EsmtpTransport(
        $config['host'],
        $config['port'],
        $config['encryption'] === 'tls',
        $dispatcher
    ))
          ->setUsername($config['user'])
          ->setPassword($config['password']);

    return new Mailer($transport);
  },
    'config' => [
        'mailer' => [
            'host' => getenv('MAILER_HOST'),
            'port' => (int)getenv('MAILER_PORT'),
            'user' => getenv('MAILER_USER'),
            'password' => getenv('MAILER_PASSWORD'),
            'encryption' => getenv('MAILER_ENCRYPTION'),
            'from' => ['email' => getenv('MAILER_FROM_EMAIL'), 'name' => getenv('MAILER_FROM_NAME')],
        ]
    ]
];