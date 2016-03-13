<?php

if (!defined('PUBLIC_PATH')) {
    exit('Invalid boot up.');
}
require __DIR__.'/../vendor/autoload.php';

$app = Laasti\HtmlApp\Application::create([
    'displayErrors' => 0,
    'views' => [
        'data' => [
            'lang' => '=Laasti\SymfonyTranslationProvider\TranslationArray'
        ]
    ]
]);

$app->container()->add('Laasti\HtmlApp\Controllers\WelcomeController');
$app->container()->add('Laasti\HtmlApp\Controllers\NotFoundController');

$app->route('GET', '/', 'Laasti\HtmlApp\Controllers\WelcomeController');
$app->exception('Laasti\Directions\Exceptions\RouteNotFoundException', 'Laasti\HtmlApp\Controllers\NotFoundController');
$app->exception('Laasti\Directions\Exceptions\MethodNotAllowedException', 'Laasti\HtmlApp\Controllers\NotFoundController');

$app->run();