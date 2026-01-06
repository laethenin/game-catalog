<?php

use Controller\AppController;
use Controller\GameApiController;
use Controller\PingApiController;
use Core\Cors;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\Router;
use Core\Session;
use Repository\GamesRepository;

session_start();
require __DIR__ . '/../autoload.php';

$config = require_once __DIR__ . '/../config/db.php';

Cors::handle();

//$path = $_SERVER['REQUEST_URI'];

$response = new Response();
$session = new Session();
$request = new Request();
$router = new Router();
$repository = new GamesRepository(Database::makePDO($config['db']));

$appController = new AppController($response, $repository, $session, $request);
$pingApiController = new PingApiController();
$gameApiController = new GameApiController($repository); // ici j'ai mis $repository car la correction de l'IDE
//demandait un argument et proposait $gamesRepository mais pour moi $repository est équivalent car il crée un nouvel
//objet GamesRepository. Je n'ai pas d'erreur mais je ne suis pas sur à 100% du choix

$registerRoutes = require __DIR__ . '/../config/routes.php';
$registerRoutes($router, $appController, $pingApiController, $gameApiController);
$router->dispatch($request, $response);

//$appController->handleRequest($path);







