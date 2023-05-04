<?php


$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    require 'app/routes/home_routes.php';
    require 'app/routes/user_routes.php';
    require 'app/routes/steps_routes.php';
    require 'app/routes/reset_password_routes.php';
    require 'app/routes/diary_routes.php';
    require 'app/api/routes/diary_ingredients_api_routes.php';
    require 'app/routes/ingredient_routes.php';
    require 'app/routes/recipe_routes.php';
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
        require 'app/views/pages/public/error/404.php';
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
