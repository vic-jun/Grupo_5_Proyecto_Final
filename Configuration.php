<?php

include_once "helper/MustachePresenter.php";
include_once "helper/Router.php";
include_once "helper/BaseDeDatos.php";

include_once "controller/LoginController.php";
include_once "controller/RegistrarController.php";
include_once "controller/ConfirmarEmailController.php";
include_once "controller/InicioController.php";

include_once "models/RegistrarModel.php";
include_once "models/ConfirmarEmailModel.php";
include_once "models/LoginModel.php";

include_once "vendor/mustache/src/Mustache/Autoloader.php";
require_once "vendor/PHPMailer-6.9.1/src/PHPMailer.php";


 class Configuration {
     // controllers
     public static function getLoginController(){
         return new LoginController(self::getLoginModel(), self::getPresenter());
     }

     public static function getRegistrarController(){
         return new RegistrarController(self::getRegistrarseModel(), self::getPresenter());
     }
     public static function getConfirmarEmailController(){
         return new ConfirmarEmailController(self::getConfirmarEmailModel(), self::getPresenter());
     }

     public static function getInicioController(){
         return new InicioController(self::getPresenter());
         //self::getInicioModel()
     }

     // models
     public static function getRegistrarseModel(){
         return new RegistrarModel(self::getBaseDeDatos());
     }

    public static function getConfirmarEmailModel(){
        return new ConfirmarEmailModel();
    }

    public static function getLoginModel(){
        return new LoginModel(self::getBaseDeDatos());
    }

     // helpers
     public static function getRouter(){
         return new Router("getLoginController" , "get");
     }

     public static function getPresenter(){
         return new MustachePresenter("views/templates");
     }

     public static function obtenerBaseDeDatos(){
         return parse_ini_file("config/base_de_datos.ini");
     }

     public static function getBaseDeDatos(){
         $config = self::obtenerBaseDeDatos();
         return new BaseDeDatos($config["servername"] , $config["user"], $config["dbname"], $config["password"]);
     }
 }