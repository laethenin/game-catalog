<?php

use Controller\AppController;
use Controller\GameApiController;
use Controller\PingApiController;
use Core\Router;
use Core\Response;
use Core\Request;

return function(Router $router, AppController $controller, PingApiController $pingApiController, GameApiController $gameApiController) {
    $router->get('/', [$controller, 'home']);
    $router->get('/add', [$controller, 'add']);
    $router->get('/games', [$controller, 'games']);
    $router->get('/random', [$controller, 'random']);
    $router->post('/add', [$controller, 'handleAddGame']);
    $router->getRegex('#^/games/(\d+)$#', function (Request $req, Response $res, array $m) use ($controller) {
        $controller->gameById((int)$m[1]);
    });

    $router->get('/api/ping', [$pingApiController, 'ping']);

    $router->get('/api/games/top', function (Request $req, Response $res) use ($gameApiController) {
        $gameApiController->top();
    });
    $router->get('/api/games/recent', function (Request $req, Response $res) use ($gameApiController) {
        $gameApiController->recent();
    });
    $router->get('/api/games/ratings', function (Request $req, Response $res) use ($gameApiController) {
        $gameApiController->ratings();
    });
};


