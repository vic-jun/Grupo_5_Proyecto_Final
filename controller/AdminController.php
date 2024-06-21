<?php

class AdminController
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
        $this->presenter->render("views/admin.mustache");
    }

    public function verificarPregunta()
    {
        $preguntas = $this->model->buscarPreguntasAverificar();
        $this->presenter->render("views/verificarPregunta.mustache", ['preguntas' => $preguntas]);
    }

    public function aprobarPregunta()
    {
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->aprobarPregunta($pregunta_id);
        header('Location: /admin/verificarPregunta');
    }

    public function rechazarPregunta()
    {
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->rechazarPregunta($pregunta_id);
        header('Location: /admin/verificarPregunta');
    }

    public function editarPreguntas(){
        $preguntas = $this->model->obtenerTodasLasPreguntasYrespuestas();
        $this->presenter->render("views/editarPreguntas.mustache", ['preguntas' => $preguntas]);
    }

    public function eliminarPregunta()
    {
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->rechazarPregunta($pregunta_id);
        header('Location: /admin/editarPreguntas');
    }

    public function modificarPregunta()
    {
        $pregunta_id = $_POST['pregunta_id'];
        $preguntas = $this->model->buscarPreguntaYrespuestaPorId($pregunta_id);
        $this->presenter->render("views/modificarPregunta.mustache", ['preguntas' => $preguntas]);
    }
    public function modificar()
    {
        $this->model->modificarPregunta($_POST['pregunta_id'], $_POST['pregunta'], $_POST['respuestaIncorrecta1'], $_POST['respuestaIncorrecta2'], $_POST['respuestaIncorrecta3'], $_POST['respuestaCorrecta']);
        header('Location: /admin/inicio');
    }

}