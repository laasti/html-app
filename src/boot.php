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
$container->addServiceProvider(new ConfigFilesProvider());

$container->addServiceProvider('Laasti\Directions\Providers\LeagueDirectionsProvider');
$container->addServiceProvider('Laasti\Peels\Providers\LeaguePeelsProvider');
$container->addServiceProvider('Laasti\Log\MonologProvider');
$container->addServiceProvider('Laasti\Lazydata\Providers\LeagueLazydataProvider');
$container->addServiceProvider('Laasti\Views\Providers\LeagueViewsProvider');
$container->addServiceProvider('Laasti\SymfonyTranslationProvider\SymfonyTranslationProvider');

$container->add('Laasti\HtmlApp\Controllers\WelcomeController');
$container->add('Laasti\HtmlApp\Controllers\NotFoundController');
$container->add('Laasti\HtmlApp\Middlewares\LocaleMiddleware')->withArguments([['en', 'fr'], 'en']);

$app = new Application($container);

$app->run(ServerRequestFactory::fromGlobals(), new Response);
