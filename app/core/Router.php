<?php
require './app/controllers/Home_Controller.php';
require './app/controllers/User_Controller.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    //Home Routes
    $r->addRoute('GET', '/', [HomeController::class, 'getHomePage']);


    //User Routes
    $r->addRoute('GET', '/user/registration', [UserController::class, 'registrationStart']);



    //Steps Routes
    $r->addRoute('GET', '/user/registration/{id}', [StepsController::class, 'prevStep']);
    $r->addRoute('POST', '/user/registration/{id}', [StepsController::class, 'nextStep']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $handlerInstance = new $handler[0]();
        $handlerInstance->{$handler[1]}($vars);
        break;
}
