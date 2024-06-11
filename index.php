<?php

    require_once "Configuration.php";

    if (session_status() == PHP_SESSION_NONE ) {
        $router = Configuration::getRouter();
    }else {
        if(!isset($_SESSION['loggedin']) && $_SESSION['loggedin']){
            $router = Configuration::getInicio();
        }else {
        session_destroy();
        $router = Configuration::getLogin();
        }
    }

    $controller = isset($_GET["controller"]) ? $_GET["controller"] : "";
    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    $router->route($controller, $action);
