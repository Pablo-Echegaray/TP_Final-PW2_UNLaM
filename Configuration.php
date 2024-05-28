<?php

include_once ("controller/UserController.php");
include_once ("controller/RegisterController.php");

include_once ("model/RegisterModel.php");
include_once ("model/UserModel.php");

include_once("helper/Database.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");
include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    //CONTROLLERS
    public static function getUserController()
    {
        return new UserController(self::getUserModel(), self::getPresenter());
    }

    public static function getRegisterController()
    {
        return new RegisterController(self::getRegisterModel(), self::getPresenter());
    }

    //MODELS
    private static function getUserModel()
    {
        return new UserModel(self::getDatabase());
    }

    private static function getRegisterModel()
    {
        return new RegisterModel(self::getDatabase());
    }

    //HELPERS
    public static function getDatabase()
    {
        $config = self::getConfig();
        return new Database($config["servername"], $config["username"], $config["password"], $config["database"]);
    }

    private static function getConfig() { return parse_ini_file("config/config.ini"); }

    public static function getRouter(){ return new Router("getUserController", "get"); }

    public static function getPresenter(){ return new MustachePresenter("view/template"); }
}