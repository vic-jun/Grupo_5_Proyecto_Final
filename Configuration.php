<?php

include_once "helper/MustachePresenter.php";
include_once "helper/Router.php";
include_once "helper/BaseDeDatos.php";

include_once "controller/LoginController.php";
include_once "controller/LogoutController.php";
include_once "controller/RegistrarController.php";
include_once "controller/ConfirmarEmailController.php";
include_once "controller/InicioController.php";
include_once "controller/SeleccionarCategoriaController.php";
include_once "controller/JuegoController.php";
include_once "controller/PerfilController.php";
include_once "controller/ErrorController.php";
include_once "controller/RankingController.php";

include_once "models/RegistrarModel.php";
include_once "models/ConfirmarEmailModel.php";
include_once "models/LoginModel.php";
include_once "models/SeleccionarCategoriaModel.php";
include_once "models/JuegoModel.php";
include_once "models/RankingModel.php";

include_once "vendor/mustache/src/Mustache/Autoloader.php";
include_once "vendor/PHPMailer-6.9.1/src/PHPMailer.php";


 class Configuration {
     // controllers
     public static function getLoginController(){
         return new LoginController(self::getLoginModel(), self::getPresenter());
     }

     public static function getLogoutController(){
         return new LogoutController(self::getPresenter());
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

     public static function getSeleccionarCategoriaController(){
         return new SeleccionarCategoriaController(self::getSeleccionarCategoriaModel(),self::getPresenter());
     }

     public static function getJuegoController(){
         return new JuegoController(self::getJuegoModel(),self::getPresenter());
     }

     public static function getPerfilController(){
         return new PerfilController(self::getPresenter());
     }

     public static function getErrorController(){
         return new ErrorController(self::getPresenter());
     }

     public static function getRankingController(){
         return new RankingController(self::getPresenter(), self::getRankingModel());
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

    public static function getSeleccionarCategoriaModel(){
        return new SeleccionarCategoriaModel(self::getBaseDeDatos());
    }

     private static function getJuegoModel(){
         return new JuegoModel(self::getBaseDeDatos());
     }

     private static function getRankingModel(){
         return new RankingModel(self::getBaseDeDatos());
     }

     // helpers
     public static function getRouter(){
             return new Router("getLoginController" , "get");
     }

     public static function getInicio(){
         return new Router("getInicioController", "get");
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