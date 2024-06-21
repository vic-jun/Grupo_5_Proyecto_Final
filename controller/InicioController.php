<?php

class InicioController
{
    private $presenter;
    private $model;

    public function __construct($presenter, $model)
    {
        $this->model = $model;
        $this->presenter = $presenter;

    }

    public function get()
    {
        session_start();
        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
            unset($_SESSION['preguntaID']);
        }

        $data = $this->model->obtenerDatosUsuario();
        $rol = $this->model->obtenerRol();


        $data['es_admin'] = ($rol === "ADMIN");
        $data['es_user'] = ($rol !== "ADMIN");

        $this->presenter->render("views/inicio.mustache", ["data" => $data]);
    }

}
