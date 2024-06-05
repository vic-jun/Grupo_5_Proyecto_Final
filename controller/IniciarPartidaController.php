<?php

class IniciarPartidaController{
    public $model;

    public $presenter;
    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function iniciarPartida(){

        $res = $this->model->iniciarPartida($_POST['categoria']);

        if (is_array($res) && isset($res["pregunta"], $res["respuestas"], $res["correcta"])) {
            $this->presenter->render("views/iniciarPartida.mustache", ['pregunta' => $res["pregunta"], 'respuestas' => $res["respuestas"], 'correcta' => $res["correcta"]]);
        } else {
            echo "Error al cargar la pregunta";
        }
    }

    public function verificar(){
        $respuesta = $this->model->verificarRespuesta($_POST['respuesta'], $_POST['correcta'], $_POST['categoria']);

        if(is_array($respuesta) && isset($respuesta['pregunta'], $respuesta['respuestas'], $respuesta['correcta'])){
            $this->presenter->render("views/iniciarPartida.mustache", ['pregunta' => $respuesta["pregunta"], 'respuestas' => $respuesta["respuestas"], 'correcta' => $respuesta["correcta"]]);
        }else{
            header("Location: /inicio");
            exit();
        }
    }
}