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
        session_start();

        $usuario = $_POST["usuario"];
        $contrasenia = $_POST["contrasenia"];

        $data = $this->model->validar($usuario, $contrasenia);

        if($data){
            $_SESSION['loggedin'] = true;
            $_SESSION['nombre_de_usuario'] = $usuario;
            $idUsuario = $this->model->buscarIdUsuario($usuario)['id'];
            $_SESSION["idUsuario"] = $idUsuario;
            header("Location: /inicio");
            exit();
        } else {
            $data = array("error" => "Usuario o contraseña incorrectos");
            $this->presenter->render("views/login.mustache", $data);
        }
    }
}