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
 }