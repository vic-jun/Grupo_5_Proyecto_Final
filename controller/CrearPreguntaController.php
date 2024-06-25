<?php

class CrearPreguntaController
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
        $data = $this->model->obtenerDatosUsuario();
        $rol = $this->model->obtenerRol();

        $data['es_admin'] = ($rol === "ADMIN");
        $data['es_user'] = ($rol !== "ADMIN");

        $this->presenter->render("views/crearPregunta.mustache", ["data" => $data]);
    }

    public function guardarPregunta()
    {
        $this->model->guardarPreguntasYrespuestas($_POST['pregunta'], $_POST['respuestaIncorrecta1'], $_POST['respuestaIncorrecta2'], $_POST['respuestaIncorrecta3'], $_POST['categoria'], $_POST['respuestaCorrecta']);
        header('Location: /inicio');
    }

    public function guardarPreguntaValidada()
    {
        $this->model->guardarPreguntasYrespuestasValidadas($_POST['pregunta'], $_POST['respuestaIncorrecta1'], $_POST['respuestaIncorrecta2'], $_POST['respuestaIncorrecta3'], $_POST['categoria'], $_POST['respuestaCorrecta']);
        header('Location: /inicio');
    }

}