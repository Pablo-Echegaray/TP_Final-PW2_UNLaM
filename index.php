<?php
include_once("Configuration.php");

$router = Configuration::getRouter();

$controller = $_GET["controller"] ?? "";
$action = $_GET["action"] ?? "";

$router->route($controller, $action);