<?php

declare(strict_types=1);

use App\Frontend\FrontendUrlGenerator;
use Psr\Container\ContainerInterface;

return [

    FrontendUrlGenerator::class => function (ContainerInterface $container) {
            return new FrontendUrlGenerator($container->get('config')['frontend']);
    },

    'config' => [
        'frontend' => [
            'url' => getenv('FRONTEND_URL'),
        ]
    ]
];