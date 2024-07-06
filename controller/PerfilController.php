<?php

class PerfilController{
    private $presenter;
    private $model;
    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get(){
        if(isset($_GET['usuario'])){
            $idUsuario = $_GET['usuario'];
            $datosPerfil = $this->model->getPerfil($idUsuario);
            if ($datosPerfil === null) {
                header('Location: /inicio');
                exit();
            }
            $this->presenter->render("views/perfil.mustache", ["perfilOtro" => $datosPerfil]);
        } else{
            if (!isset($_SESSION['idUsuario'])) {
                header('Location: /login');
                exit();
            }
            $idUsuario = $_SESSION['idUsuario'];
            if (empty($idUsuario)) {
                header('Location: /login');
                exit();
            }
            $datosPerfil = $this->model->getPerfil($idUsuario);
            if ($datosPerfil === null) {
                header('Location: /registrar');
                exit();
            }
            $this->presenter->render("views/perfil.mustache", ["perfil" => $datosPerfil]);
        }
    }

}
