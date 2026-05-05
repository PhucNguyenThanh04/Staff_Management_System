<?php

/**
 * DO NOT MODIFY ANYTHING HERE
 */

session_start();

require 'config/timezone.php';
require 'config/role.php';
require 'config/attendance.php';
require 'helpers/index.php';
require_once 'helpers/LanguageHelper.php';

// Initialize language system
LanguageHelper::init();

date_default_timezone_set(DEFAULT_TIMEZONE);

$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'DefaultController';
$controllerNameParts = explode('-', $controllerName);
foreach ($controllerNameParts as $index => $controllerNamePart) {
    $controllerNameParts[$index] = ucfirst($controllerNamePart);
}
$controllerName = implode('', $controllerNameParts);

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

/**
 * Require necessary base file
 */
$phpFile = preg_replace('/index.php/', '', $_SERVER['PHP_SELF']);

$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';

define('BASE_PATH', $protocol . "://" . $_SERVER['HTTP_HOST'] . $phpFile);

/**
 * Require necessary base file
 */


/**
 * Load controller tương ứng
 */
// $module = $_GET['module'] == 'admin' ? 'Admin' : 'Web' ;
// require "app/Controllers/$module/$controllerName.php";
require "app/Controllers/$controllerName.php";

/**
 * Require controller
 */

$controllerInstance = new $controllerName();

echo $controllerInstance->$action();
