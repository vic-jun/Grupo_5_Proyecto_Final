<?php

    require_once "Configuration.php";
    session_start();

    $router = Configuration::getRouter();
    $validControllers = ["registrar", "login", "ranking", "perfil", "confirmarEmail", "resumenPartida", "seleccionarCategoria", "editor", "crearPregunta", "editarPregunta", "error", "inicio", "juego", "modificarPregunta", "perfil", "preguntasReportadas", "ranking", "resumenPartida", "seleccionarCategoria", "verificarPregunta", "logout", "partidas"];
    $action = " ";
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']){
        if($_SESSION['idUsuario'] != null){
            $controller = "Inicio";
            if(isset($_GET['controller'])){

                $controller = $_GET['controller'];
                if (!in_array($controller, $validControllers)) {
                    header("Location: /inicio");
                }else {
                    switch ($controller) {
                        case "registrar":
                        case "login":
                        header("Location: /inicio");
                            break;
                    }

                    if ($_SESSION['rol'] == "EDITOR") {
                        switch ($controller) {
                            case "jugar":
                            case "confirmarEmail":
                            case "resumenPartida":
                            case "seleccionarCategoria":
                            case "partidas":
                            header("Location: /inicio");
                                break;
                        }
                    } elseif ($_SESSION['rol'] == "usuario") {
                        switch ($controller) {
                            case "editor":
                                header("Location: /inicio");
                                break;
                        }
                    }elseif ($_SESSION['rol'] == "ADMIN") {
                        switch ($controller) {
                            case "jugar":
                            case "confirmarEmail":
                            case "resumenPartida":
                            case "seleccionarCategoria":
                            case "partidas":
                            case "editor":
                                header("Location: /inicio");
                                break;
                        }
                    }
                    $action = isset($_GET['action']) ? $_GET['action'] : " ";
                }
            }
        }else {
            $controller = "Login";
            if(isset($_GET['controller'])){
                $controller = $_GET['controller'];

                switch ($controller){
                    case "confirmarEmail":
                    case "crearPregunta":
                    case "editarPregunta":
                    case "error":
                    case "inicio":
                    case "juego":
                    case "modificarPregunta":
                    case "perfil":
                    case "preguntasReportadas":
                    case "ranking":
                    case "resumenPartida":
                    case "seleccionarCategoria":
                    case "verificarPregunta":
                        header("Location: /login");
                        break;
                }

                $action = isset($_GET['action']) ? $_GET['action'] : " ";
            }
        }
    }else{
        $controller = "Login";
        if(isset($_GET['controller'])){
            $controller = $_GET['controller'];

            switch ($controller){
                case "confirmarEmail":
                case "crearPregunta":
                case "editarPregunta":
                case "error":
                case "inicio":
                case "juego":
                case "modificarPregunta":
                case "perfil":
                case "preguntasReportadas":
                case "ranking":
                case "resumenPartida":
                case "seleccionarCategoria":
                case "verificarPregunta":
                    header("Location: /login");
                    break;
            }

            $action = isset($_GET['action']) ? $_GET['action'] : " ";
        }
    }

    $router->route($controller, $action);
