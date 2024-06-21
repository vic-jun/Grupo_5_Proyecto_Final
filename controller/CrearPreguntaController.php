<?php

class CrearPreguntaController{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->model = $model;
        $this->presenter = $presenter;

    }

    public function get(){
        $this->presenter->render("views/crearPregunta.mustache");
    }

    public function guardarPregunta(){
        $this->model->guardarPreguntasYrespuestas($_POST['pregunta'], $_POST['respuestaIncorrecta1'], $_POST['respuestaIncorrecta2'], $_POST['respuestaIncorrecta3'], $_POST['categoria'], $_POST['respuestaCorrecta']);
        $this->presenter->render("views/inicio.mustache");
    }

}