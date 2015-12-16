<?php

use Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider;
use Silex\Application;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request as Request;


// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

require_once("credentials.php");
$app->register(
    // you can customize services and options prefix with the provider first argument (default = 'pdo')
    new PDOServiceProvider('pdo'),
    array(
        'pdo.server'   => array(
            // PDO driver to use among : mysql, pgsql , oracle, mssql, sqlite, dblib
            'driver'   => 'mysql',
            'host'     => HOST,
            'dbname'   => BASE,
            'port'     => PORT,
            'user'     => USER,
            'password' => PASSWD,
        ),
        // optional PDO attributes used in PDO constructor 4th argument driver_options
        // some PDO attributes can be used only as PDO driver_options
        // see http://www.php.net/manual/fr/pdo.construct.php
        'pdo.options' => array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        ),
        // optional PDO attributes set with PDO::setAttribute
        // see http://www.php.net/manual/fr/pdo.setattribute.php
        'pdo.attributes' => array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ),
    )
);
// get PDO connection
$pdo = $app['pdo'];
// exemple appel de query sql

/*
$app->get('/', function() use($app)
{
    if(null === $user = $app['session']->get('user'))
        return $app->redirect('/login');
 
    return $app['twig']->render('main.html', array('name' => $user['username']));
});

$app->get('/login', function() use($app)
{
    return $app['twig']->render('getLogin.html');
});
 
$app->post('/login', function(Request $request) use($app)
{
    $usr = $request->get('username');
    $pas = $request->get('password');
 
    //TODO: authenticate
 
    $app['session']->set('user', array('username'=> $usr));
    return $app['twig']->render('postLogin.html', array('username' => $usr));
 
});
 
$app->get('/logout', function() use($app)
{
    $app['session']->set('user', null);
    return $app['twig']->render('logout.html', array());
 
});
*/
