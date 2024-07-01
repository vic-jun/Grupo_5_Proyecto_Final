<?php

class EditorController{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("views/admin.mustache");
    }

    public function verificarPregunta(){
        $preguntas = $this->model->buscarPreguntasAverificar();
        $this->presenter->render("views/verificarPregunta.mustache", ['preguntas' => $preguntas]);
    }

    public function aprobarPregunta(){
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->aprobarPregunta($pregunta_id);
        header('Location: /admin/verificarPregunta');
    }

    public function rechazarPregunta(){
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->rechazarPregunta($pregunta_id);
        header('Location: /admin/verificarPregunta');
    }

    public function editarPreguntas(){
        $preguntas = $this->model->obtenerTodasLasPreguntasYrespuestas();
        $this->presenter->render("views/editarPreguntas.mustache", ['preguntas' => $preguntas]);
    }

    public function eliminarPregunta(){
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->rechazarPregunta($pregunta_id);
        header('Location: /admin/editarPreguntas');
    }

    public function modificarPregunta(){
        $pregunta_id = $_POST['pregunta_id'];
        $data = $this->model->buscarPreguntaYrespuestaPorId($pregunta_id);

        $this->presenter->render("views/modificarPregunta.mustache", ['data' => $data,'categoriaEsHistoria' => $data['categoria'] === 'HISTORIA',
            'categoriaEsGeografia' => $data['categoria'] === 'GEOGRAFIA',
            'categoriaEsCiencia' => $data['categoria'] === 'CIENCIA',
            'categoriaEsArte' => $data['categoria'] === 'ARTE',
            'categoriaEsDeporte' => $data['categoria'] === 'DEPORTE',
            'categoriaEsEntretenimiento' => $data['categoria'] === 'ENTRETENIMIENTO']);
    }

    public function modificar(){
        $this->model->modificarPregunta($_POST['pregunta_id'], $_POST['pregunta'], $_POST['respuesta0'], $_POST['respuesta1'], $_POST['respuesta2'], $_POST['respuesta3'], $_POST['respuestaCorrecta'], $_POST['categoria']);
        header('Location: /inicio');
    }

    public function preguntasReportadas(){
        $preguntas = $this->model->buscarPreguntasReportadas();
        $this->presenter->render("views/preguntasReportadas.mustache", ['preguntas' => $preguntas]);
    }

    public function aprobarReporte(){
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->rechazarPregunta($pregunta_id);
        header('Location: /admin/preguntasReportadas');
    }

    public function rechazarReporte(){
        $pregunta_id = $_POST['pregunta_id'];
        $this->model->rechazarReporte($pregunta_id);
        header('Location: /admin/preguntasReportadas');
    }

}