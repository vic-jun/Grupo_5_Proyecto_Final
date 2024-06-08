<?php

    require_once "Configuration.php";

    if (session_status() == PHP_SESSION_NONE) {
        $router = Configuration::getRouter();
    }else {
        $router = Configuration::getInicio();
    }

    $controller = isset($_GET["controller"]) ? $_GET["controller"] : "";
    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    $router->route($controller, $action);
