<?php
//main controller
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
//en mode debug on ajoute celle ligne
require_once __DIR__.'/../app/config/dev.php';

require_once __DIR__.'/../app/app.php'; // appel de app

//require_once __DIR__.'/../app/routes.php'; // appel des routes
require_once __DIR__.'/../app/routes_dev.php'; // appel des routes

$app->run();