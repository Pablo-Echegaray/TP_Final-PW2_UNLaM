<?php
include_once("Configuration.php");

//session_start();

$router = Configuration::getRouter();

$controller = $_GET["controller"] ?? "";
$action = $_GET["action"] ?? "";

$router->route($controller, $action);