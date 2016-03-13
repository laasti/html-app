<?php

if (!defined('PUBLIC_PATH')) {
    exit('Invalid boot up.');
}
require __DIR__.'/../vendor/autoload.php';
$configFiles = [__DIR__.'/../resources/config/default.php'];

if (file_exists(__DIR__.'/../config.ini')) {
    $config = parse_ini_file(__DIR__.'/../config.ini', true);
    $configFiles[] = __DIR__.'/../resources/config/'.$config['environment'].'.php';
    $configFiles[] = __DIR__.'/../config.ini';
}

$app = Laasti\HtmlApp\Application::create($configFiles);

$app->container()->add('Laasti\HtmlApp\Controllers\WelcomeController');
$app->container()->add('Laasti\HtmlApp\Controllers\NotFoundController');
$app->container()->add('Laasti\HtmlApp\Middlewares\LocaleMiddleware')->withArguments([['en', 'fr'], 'en']);

$app->middleware('Laasti\HtmlApp\Middlewares\LocaleMiddleware', 'exceptions');
$app->middleware('Laasti\HtmlApp\Middlewares\LocaleMiddleware');

$app->route('GET', '/', 'Laasti\HtmlApp\Controllers\WelcomeController');
$app->route('GET', '/fr', 'Laasti\HtmlApp\Controllers\WelcomeController');

$app->exception('Laasti\Directions\Exceptions\RouteNotFoundException', 'Laasti\HtmlApp\Controllers\NotFoundController');
$app->exception('Laasti\Directions\Exceptions\MethodNotAllowedException', 'Laasti\HtmlApp\Controllers\NotFoundController');

$app->run();