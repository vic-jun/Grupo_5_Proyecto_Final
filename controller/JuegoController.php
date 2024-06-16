<?php

class JuegoController
{
    public $model;
    public $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function partida(){
        session_start();

        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
            unset($_SESSION["start_time"]);
            $this->guardarPuntajeFinal();
            unset($_SESSION["puntaje"]);
        }

        if (!isset($_SESSION["puntaje"])) {
            $_SESSION["puntaje"] = 0;
        }

        if (!isset($_SESSION["categoria"]) || isset($_POST["categoria"])) {
            $_SESSION["categoria"] = $_POST["categoria"];
        }

        $_SESSION["start_time"] = time();

        $res = $this->model->iniciarPartida($_SESSION["categoria"]);
        $res["time_left"] = $this->getTimeLeft();

        $this->presenter->render("views/juego.mustache", $res);
    }

    public function verificar(){
        session_start();

        if (!isset($_SESSION["start_time"])) {
            header("Location: /inicio");
            exit();
        }

        $pregunta = $_POST["pregunta"];
        $respuesta = $_POST["respuesta"];
        $correcta = $_POST["correcta"];

        if ($this->getTimeLeft() <= 0) {
            header("Location: /inicio?timeout=true");
            exit();
        }

        $resultado = $this->model->verificarRespuesta($respuesta, $correcta);

        if ($resultado) {
            $puntaje = $this->model->generarPuntaje($pregunta);
            $_SESSION["puntaje"] += $puntaje;
            header("Location: /Juego/partida");
        } else {
            $this->guardarPuntajeFinal();
            $this->presenter->render("views/resumenPartida.mustache", ["puntaje" => $_SESSION["puntaje"], "categoria" => $_SESSION["categoria"]]);
            unset($_SESSION["puntaje"]);
        }
        exit();
    }

    public function timeLeft(){
        session_start();
        echo json_encode(["time_left" => $this->getTimeLeft()]);
    }

    private function getTimeLeft(){
        if (!isset($_SESSION["start_time"])) {
            return 0;
        }

        $duration = 30;
        $elapsed = time() - $_SESSION["start_time"];
        return max($duration - $elapsed, 0);
    }

    private function guardarPuntajeFinal(){
        if (isset($_SESSION["idUsuario"]) && isset($_SESSION["puntaje"])) {
            $idUsuario = $_SESSION["idUsuario"];
            $puntaje = $_SESSION["puntaje"];
            $this->model->guardarPuntajeMaximoEnBD($idUsuario, $puntaje);
        }
    }

}
