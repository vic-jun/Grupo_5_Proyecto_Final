<?php

class InicioController{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true')
            unset($_SESSION['preguntaID']);

        $data = $this->model->obtenerDatosUsuario();
        $rol = $this->model->obtenerRol();

        $data['es_admin'] = ($rol === "EDITOR");
        $data['es_user'] = ($rol === "usuario");

        $this->presenter->render("views/inicio.mustache", ["data" => $data]);
    }

}
