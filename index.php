<?php

define('SITE_DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);
define('SITE_NAME', 'Крестики нолики');
define('SITE_HOST', $_SERVER['HTTP_HOST']);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'game');

function autoload($className)
{
    $className = str_replace('\\', DS, $className);
    $file = SITE_DIR . DS . $className . '.php' ;
    if(file_exists($file))
    {
        include_once $file;
    }
}
spl_autoload_register('autoload');

$controller = 'index';
$action = 'index';
$parameters = null;
session_start();

if( isset($_GET['route'])) //определение необходимого контролера и метода и URL
{
    $route = explode('/', $_GET['route']);
    if(isset($route[0]))
    {
        $controller = $route[0];
    }
    if(isset($route[1]))
    {
        $action = $route[1];
    }
    if(isset($route[2]))
    {
        $parameters = $route[2];
    }
}
$controller=ucfirst($controller);
$controllerName = "\\Controller\\{$controller}Controller";
$controllerObj = new $controllerName();
if(is_callable(array($controllerObj, $action)))
{
    $controllerObj->$action($parameters);
} else
{
    echo 'Starting default!';
    $controllerObj->index($parameters);
}