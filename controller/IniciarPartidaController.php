<?php

class IniciarPartidaController{
    public $model;

    public $presenter;
    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function iniciarPartida(){
        if(isset($_POST['categoria'])){
            $categoria = $_POST['categoria'];
        }else if(isset($_SESSION['categoria'])){
            $categoria = $_SESSION['categoria'];
        }else{
            echo "no tengo sesion";
            header("Location: /login");
            exit();
        }

        $res = $this->model->iniciarPartida($categoria);

        if (is_array($res) && isset($res["pregunta"], $res["respuestas"], $res["correcta"])) {
            $this->presenter->render("views/iniciarPartida.mustache", ['pregunta' => $res["pregunta"], 'respuestas' => $res["respuestas"], 'correcta' => $res["correcta"], "categoria" => $categoria]);
        } else {
            echo "Error al cargar la pregunta";
        }
    }

    public function verificar(){
        $respuesta = $this->model->verificarRespuesta($_POST['respuesta'], $_POST['correcta'], $_POST['categoria']);

        if(is_string($respuesta)){
            $_SESSION['categoria'] = $respuesta;
            header("Location: /iniciarPartida");
            exit();
        }else{
            header("Location: /inicio");
        }
    }
}