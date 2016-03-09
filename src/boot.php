<?php

if (!defined('PUBLIC_PATH')) {
    exit('Invalid boot up.');
}
require __DIR__.'/../vendor/autoload.php';

$app = Laasti\HtmlApp\Application::create([
    'views' => [
        'data' => [
            'lang' => '=Laasti\SymfonyTranslationProvider\TranslationArray'
        ]
    ]
]);

$app->container()->add('Laasti\HtmlApp\Controllers\WelcomeController');

$app->route('GET', '/', 'Laasti\HtmlApp\Controllers\WelcomeController');

$app->run();