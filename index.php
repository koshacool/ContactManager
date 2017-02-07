<?php
namespace Core;

define('ROOT', dirname(__FILE__));//Save to value root directory

//Include settings & autoload classes
include ROOT . '/Config/Config.php';
include ROOT . '/Config/Autoload.php';

spl_autoload_register('loadClass', true, true);// Register the autoloader

//start router
$router = new Router();
$router -> start();