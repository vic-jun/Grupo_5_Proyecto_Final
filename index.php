<?php

    require_once "Configuration.php";
    if (session_status() == PHP_SESSION_NONE) {
       $router = Configuration::getInicio();
    }else {
        $router = Configuration::getRouter();
    }

    $controller = isset($_GET["controller"]) ? $_GET["controller"] : "";
    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    $router->route($controller, $action);
