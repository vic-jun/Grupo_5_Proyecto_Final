<?php

include_once "helper/MustachePresenter.php";
include_once "helper/Router.php";
include_once "helper/BaseDeDatos.php";

include_once "controller/LoginController.php";
include_once "controller/RegistrarController.php";

include_once "models/RegistrarModel.php";
include_once "models/ConfirmarEmailModel.php";

include_once "vendor/mustache/src/Mustache/Autoloader.php";


 class Configuration {
     // controller

     public static function getLoginController(){
         return new LoginController(self::getPresenter());
     }

     public static function getRegistrarController(){
         return new RegistrarController(self::getRegistrarseModel(),self::getPresenter());
     }
     public static function getConfirmarEmailController(){
         return new ConfirmarEmailController(self::getConfirmarEmailModel(),self::getPresenter());
     }
     // model

     public static function getRegistrarseModel(){
         return new RegistrarModel(self::getBaseDeDatos());
     }

    public static function getConfirmarEmailModel()
    {
        return new ConfirmarEmailModel();
    }
     // helper

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