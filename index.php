<?php

    require_once "Configuration.php";

    $router = Configuration::getRouter();

    $controller = isset($_GET["controller"]) ? $_GET["controller"] : "";
    $action = isset($_GET["action"]) ? $_GET["action"] : "";

    $router->route($controller, $action);
