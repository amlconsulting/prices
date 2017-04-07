<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../application/config/paths.php';
require VENDOR_PATH . 'autoload.php';
require APP_PATH . 'autoload.php';

session_start();

$app = new \Slim\App(['settings' => require CONFIG_PATH . 'settings.php']);

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

$container['csrf'] = function($c) {
    return new \Slim\Csrf\Guard('llrcsrf');
};

$app->add($container->get('csrf'));

/*$app->add(function($request, $response, $next) {
    if($response->getStatusCode() === 404) {
        return $this->view->render($response, 'error/error.phtml', [
            'error' => 'Page Not Found!',
            'message' => 'The page you were looking for does not exist.'
        ]);
    }
});*/

$app->group('/api', function() {
    $this->get('/validateuseredit', 'ApiController:validateUserEdit');
});

$app->group('/admin', function() {
    $this->map(['GET', 'POST'], '/login', 'AdminController:login');
    $this->get('/logout', 'AdminController:logout');
    $this->get('/user', 'AdminController:user');
    $this->map(['GET', 'POST'], '/changepassword', 'AdminController:changePassword');
    $this->map(['GET', 'POST'], '/edituser', 'AdminController:editUser');
    $this->get('/items', 'AdminController:items');
    $this->map(['GET', 'POST'], '/additem', 'AdminController:addItem');
    $this->map(['GET', 'POST'], '/edititem/{id}', 'AdminController:editItem');
    $this->map(['GET', 'POST'], '/deleteitem/{id}', 'AdminController:deleteItem');
})->add(function($request, $response, $next) {
    if(!isset($_SESSION['user']) && $request->getRequestTarget() !== '/admin/login') {
        return $response->withRedirect('/admin/login');
    } else {
        return $next($request, $response);
    }
});

$app->get('/admin', function($request, $response, $next) {
    return $response->withRedirect('/admin/items');
});

$app->get('/[{user}]', 'HomeController:getUserItemsByUserName');

$app->run();