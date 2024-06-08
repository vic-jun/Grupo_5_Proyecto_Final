<?php

class IniciarPartidaController{

    public $model;

    public $presenter;
    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function iniciarPartida(){

        session_start();

        if(isset($_POST['categoria'])){
            $_SESSION['categoria'] = $_POST['categoria'];
        }

        $res = $this->model->iniciarPartida($_SESSION['categoria']);

        if (is_array($res) && isset($res["pregunta"], $res["respuestas"], $res["correcta"])) {
            $this->presenter->render("views/iniciarPartida.mustache", ['pregunta' => $res["pregunta"], 'respuestas' => $res["respuestas"], 'correcta' => $res["correcta"]]);
        } else {
            echo "Error al cargar la pregunta";
        }
    }

    public function verificar(){

        $respuesta = $this->model->verificarRespuesta($_POST['respuesta'], $_POST['correcta']);

        if($respuesta){
            header("Location: /iniciarPartida");
        }else{
            header("Location: /inicio");
        }
        exit();
    }
}