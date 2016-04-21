<?php

use Laasti\Core\Providers\ConfigFilesProvider;
use Laasti\Http\Application;
use League\Container\Container;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

if (!defined('PUBLIC_PATH')) {
    exit('Invalid boot up.');
}
require __DIR__ . '/../vendor/autoload.php';
$configFiles = [__DIR__ . '/../resources/config/default.php'];

if (file_exists(__DIR__ . '/../config.ini')) {
    $config = parse_ini_file(__DIR__ . '/../config.ini', true);
    $configFiles[] = __DIR__ . '/../resources/config/' . $config['environment'] . '.php';
    $configFiles[] = __DIR__ . '/../config.ini';
}
$container = new Container;

$container->add('config_files', $configFiles);

$container->addServiceProvider('Laasti\HtmlEdition\ServiceProvider');

$container->add('Laasti\HtmlApp\Controllers\WelcomeController');
$container->add('Laasti\HtmlApp\Controllers\NotFoundController');
$container->add('Laasti\HtmlApp\Middlewares\LocaleMiddleware')->withArguments([['en', 'fr'], 'en']);

$app = new Application($container);

$app->run(ServerRequestFactory::fromGlobals(), new Response);
