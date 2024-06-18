<?php

class InicioController
{

    // private $model;
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

        $this->presenter->render("views/inicio.mustache", ["data" => $data]);
    }

}
