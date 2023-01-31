<?php
require_once './model/Database.php';
require_once './controller/UserController.php';
require_once './controller/LoginController.php';

$urlParts = explode('/', $_SERVER['REQUEST_URI']);
$requestMethod = $_SERVER["REQUEST_METHOD"];

$apiName = $urlParts[1];
$userId = null;
if (isset($urlParts[2]) && (ctype_digit($urlParts[2]) || $urlParts[2] === '')) {
	$userId = (int) $urlParts[2];
}

if ($apiName === 'users') {
	$userController = new UserController($requestMethod, $userId);
	$userController->processRequest();
} elseif ($apiName === 'login') {
	$loginController = new LoginController($requestMethod);
	$loginController->processRequest();
} else {
	header("HTTP/1.1 404 Not Found");
	exit();
}
