<?php

return [
    'defaultLocale' => 'en',
    'debug' => false,
    'errorReporting' => E_ALL,
    'displayErrors' => false,
    'views' => [
        'data' => require __DIR__.'/viewdata.php',
        'locations' => [
            __DIR__.'/../views'
        ],
        'data_class' => 'Laasti\Views\Data\LazyData'
    ],
    'booboo' => [
        'exception_handlers' => [
            'Laasti\Directions\Exceptions\RouteNotFoundException' => 'Laasti\HtmlApp\Controllers\NotFoundController',
            'Laasti\Directions\Exceptions\MethodNotAllowedException' => 'Laasti\HtmlApp\Controllers\NotFoundController',
        ],
        //How errors are displayed in the output
        'formatters' => [
            'League\BooBoo\Formatter\HtmlTableFormatter' => E_ALL
        ],
        //How errors are handled (logging, sending e-mails...)
        'handlers' => [
            'League\BooBoo\Handler\LogHandler'
        ]
    ],
    'peels' => [
        'http' => [
            'runner' => 'Laasti\Peels\Http\HttpRunner',
            'middlewares' => [
                'directions.default::find',
                'Laasti\HtmlApp\Middlewares\LocaleMiddleware',
                'directions.default::dispatch'
            ]
        ],
        'exceptions' => [
            'runner' => 'Laasti\Peels\Http\HttpRunner',
            'middlewares' => [
                'Laasti\HtmlApp\Middlewares\LocaleMiddleware',
            ]
        ],
    ],
    'translation' => [
        'loaders' => [
            'array' => 'Symfony\Component\Translation\Loader\ArrayLoader',
            'json' => 'Symfony\Component\Translation\Loader\JsonFileLoader'
        ],
        'resources' => [
            'en' => [
                ['json', __DIR__.'/../languages/en/messages.json'],
            ],
            'fr' => [
                ['json', __DIR__.'/../languages/fr/messages.json'],
            ]
        ],
    ],
    'directions' => [
        'default' => [
            'strategy' => 'Laasti\Directions\Strategies\PeelsStrategy',
            'routes' => [
                ['GET', '/', 'Laasti\HtmlApp\Controllers\WelcomeController'],
                ['GET', '/fr', 'Laasti\HtmlApp\Controllers\WelcomeController'],
            ]
        ]
    ]
];