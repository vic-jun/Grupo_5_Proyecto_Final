<?php

class LoginController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("views/login.mustache");
    }

    public function logearse(){
        $data = $this->model->validar($_POST["usuario"], $_POST["contrasenia"]);

        if($data){
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['nombre_de_usuario'] = $_POST["usuario"];
            $idUsuario = $this->model->buscarIdUsuario($_POST["usuario"]);
            $_SESSION["idUsuario"] = $idUsuario;
            header("Location: /inicio");
            exit();
        } else {
            $data = array("error" => "Usuario o contraseña incorrectos");
            $this->presenter->render("views/login.mustache", $data);
        }
    }
}