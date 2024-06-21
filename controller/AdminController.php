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
        echo '<pre>';
        print_r($preguntas); // Debug: Verificar la estructura de datos
        echo '</pre>';
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

}