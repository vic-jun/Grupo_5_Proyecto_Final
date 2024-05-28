<?php

include_once "helper/MustachePresenter.php";
include_once "helper/Router.php";
include_once "controller/LoginController.php";
include_once "vendor/mustache/src/Mustache/Autoloader.php";
include_once "controller/RegistrarController.php";

 class Configuration {
     // controller

     public static function getLoginController(){
         return new LoginController(self::getPresenter());
     }

     public static function getRegistrarController(){
         return new RegistrarController(self::getPresenter());
     }

     // model

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