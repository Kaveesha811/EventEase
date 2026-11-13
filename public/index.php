<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/database.php';

spl_autoload_register(function ($class) {
    $modelPath = '../app/models/' . $class . '.php';
    $controllerPath = '../app/controllers/' . $class . '.php';
    if (file_exists($modelPath)) {
        require_once $modelPath;
    } elseif (file_exists($controllerPath)) {
        require_once $controllerPath;
    }
});

$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'AuthController';
$action = isset($urlParts[1]) ? $urlParts[1] : 'login';
$params = array_slice($urlParts, 2);

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        die("Method '$action' not found in $controllerName");
    }
} else {
    die("Controller '$controllerName' not found");
}
?>