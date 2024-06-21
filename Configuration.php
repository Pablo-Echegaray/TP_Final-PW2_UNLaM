<?php
include_once ("controller/UserController.php");
include_once ("controller/RegisterController.php");
include_once ("controller/PartidaController.php");
include_once("controller/RankingController.php");
include_once("controller/PreguntaController.php");

include_once ("model/RegisterModel.php");
include_once ("model/UserModel.php");
include_once ("model/PartidaModel.php");
include_once ("model/RankingModel.php");
include_once ("model/PreguntaModel.php");
include_once("model/AdminModel.php");

include_once("helper/Database.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");
include_once("helper/DataConversion.php");
include_once('vendor/mustache/src/Mustache/Autoloader.php');


class Configuration
{
    //CONTROLLERS
    public static function getUserController()
    {
        return new UserController(self::getUserModel(), self::getAdminModel(), self::getPreguntaModel(), self::getPresenter());
    }

    public static function getRegisterController()
    {
        return new RegisterController(self::getRegisterModel(), self::getPresenter());
    }

    public static function getPartidaController()
    {
        return new PartidaController(self::getPartidaModel(), self::getPresenter());
    }

    public static function getRankingController()
    {
        return new RankingController(self::getRankingModel(), self::getPresenter());
    }

    public static function getPreguntaController(){
        return new PreguntaController(self::getPreguntaModel(), self::getPresenter());
    }

    //MODELS
    private static function getUserModel()
    {
        return new UserModel(self::getDatabase());
    }

    private static function getAdminModel()
    {
        return new AdminModel(self::getDatabase());
    }

    private static function getRegisterModel()
    {
        return new RegisterModel(self::getDatabase());
    }
    private static function getPartidaModel()
    {
        return new PartidaModel(self::getDatabase());
    }

    public static function getRankingModel()
    {
        return new RankingModel(self::getDatabase());
    }

    public static function getPreguntaModel(){
        return new PreguntaModel(self::getDatabase(), self::getDataConversion());
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

    public static function getDataConversion(){ return new DataConversion(); }
}