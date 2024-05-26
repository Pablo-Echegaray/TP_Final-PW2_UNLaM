<?php

include_once("helper/Database.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");
include_once('vendor/mustache/src/Mustache/Autoloader.php');

include_once ("controller/UserController.php");

class Configuration
{
    //CONTROLLERS
    public static function getUserController()
    {
        return new UserController(self::getPresenter());
    }

    //HELPERS
    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["database"]);
    }

    private static function getConfig() { return parse_ini_file("config/config.ini"); }

    public static function getRouter(){ return new Router("getUserController", "register"); }

    public static function getPresenter(){ return new MustachePresenter("view/template"); }
}