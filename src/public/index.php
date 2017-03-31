<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../application/config/paths.php';
require VENDOR_PATH . 'autoload.php';
require APP_PATH . 'autoload.php';

$app = new Slim\App(['settings' => require CONFIG_PATH . 'settings.php']);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('MainLogger');
    $file_handler = new \Monolog\Handler\StreamHandler(LOG_PATH . 'app.log');
    $logger->pushHandler($file_handler);

    return $logger;
};

$container['db'] = function($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['db_host'] . ";dbname=" . $db['db_name'], $db['db_user'], $db['db_pass']);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return $pdo;
};

$container['view'] = new \Slim\Views\PhpRenderer(VIEW_PATH);

$app->group('/user', function($request, $response) {
    $this->get('', 'UserController:getAll');
    $this->get('/seed', 'UserController:seed');
});

$app->group('/item', function() {
    $this->get('', 'ItemController:getAllByUserId');
});

$app->run();