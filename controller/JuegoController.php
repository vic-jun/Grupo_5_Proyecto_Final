<?php

class JuegoController{

    public $model;

    public $presenter;
    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get()
    {
        session_start();

        if(!isset($_SESSION["categoria"])){
            $_SESSION["categoria"] = $_POST["categoria"];
        }

        $res = $this->model->iniciarPartida($_SESSION["categoria"]);

        $this->presenter->render("views/juego.mustache", $res);
    }

    public function verificar(){
        session_start();

        $respuesta = $_POST["respuesta"];
        $correcta = $_POST["correcta"];

        $resultado = $this->model->verificarRespuesta($respuesta, $correcta);

        if($resultado){
            header("Location: /Juego");
        } else {
            echo "pase por aca";
            header("Location: /Inicio");
        }
    }
}