<?php

return [
    'defaultLocale' => 'en',
    'debug' => true,
    'errorReporting' => E_ALL,
    'displayErrors' => true,
    'views' => [
        'data' => require __DIR__.'/viewdata.php',
        'locations' => [
            __DIR__.'/../views'
        ],
        'data_class' => 'Laasti\Views\Data\LazyData'
    ],
    'booboo' => [
        'pretty_page' => 'error_formatter',
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
            'middlewares' => []
        ],
        'exceptions' => [
            'runner' => 'Laasti\Peels\Http\HttpRunner',
            'middlewares' => []
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
            'routes' => []
        ]
    ]
];