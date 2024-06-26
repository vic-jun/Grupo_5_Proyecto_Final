<?php

class JuegoController{
    public $model;
    public $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        header("Location: /seleccionarCategoria");
        exit();
    }

    public function partida(){
        if (isset($_SESSION['preguntaID'])) {
            $res = $this->model->traerPreguntaEspecifica($_SESSION['preguntaID'], $_SESSION["categoria"]);
            $_SESSION["cantRespuestasContestadas"] = 0;
            $_SESSION["cantRespuestasCorrectas"] = 0;
            $_SESSION["correctasBloque"] = 0;
            if (is_array($res) && count($res) > 0) {
                $res["time_left"] = $this->getTimeLeft();
                $this->presenter->render("views/juego.mustache", $res);
                exit();
            }
        }

        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
            unset($_SESSION["start_time"]);
            $this->guardarPuntajeFinal();
            unset($_SESSION["puntaje"]);
        }

        if (!isset($_SESSION["puntaje"]))
            $_SESSION["puntaje"] = 0;

        if (!isset($_SESSION["categoria"]) || isset($_POST["categoria"]))
            $_SESSION["categoria"] = $_POST["categoria"];

        $_SESSION["start_time"] = time();

        $res = $this->model->iniciarPartida($_SESSION["categoria"]);
        if(is_array($res))
            $res["time_left"] = $this->getTimeLeft();

        $this->presenter->render("views/juego.mustache", $res);
    }

    public function verificar(){
        unset($_SESSION['preguntaID']);

        if (!isset($_SESSION["start_time"])) {
            header("Location: /inicio");
            exit();
        }

        $accion = $_POST["accion"];
        $pregunta = $_POST["pregunta"];

        if ($accion == "reportar") {
            $this->model->reportarPregunta($pregunta);
            header("Location: /juego/partida");
            exit();
        }

        $respuesta = $_POST["respuesta"];
        $correcta = $_POST["correcta"];

        if ($this->getTimeLeft() <= 0) {
            header("Location: /inicio?timeout=true");
            exit();
        }

        if (!isset($_SESSION["cantRespuestasContestadas"]))
            $_SESSION["cantRespuestasContestadas"] = 0;

        if (!isset($_SESSION["cantRespuestasCorrectas"]))
            $_SESSION["cantRespuestasCorrectas"] = 0;

        if (!isset($_SESSION["correctasBloque"]))
            $_SESSION["correctasBloque"] = 0;

        $resultado = $this->model->verificarRespuesta($respuesta, $correcta);

        if ($resultado) {
            $this->model->actualizarCantidadCorrectas($pregunta);

            $this->model->calcularDificultadPregunta($pregunta);

            $puntaje = $this->model->generarPuntaje($pregunta);
            $_SESSION["puntaje"] += $puntaje;
            $_SESSION["cantRespuestasContestadas"]++;
            $_SESSION["cantRespuestasCorrectas"]++;
            $_SESSION["correctasBloque"]++;
            header("Location: /juego/partida");
        } else {
            $this->model->actualizarCantidadIncorrectas($pregunta);

            $this->guardarPuntajeFinal();
            $this->model->cantRespuestasContestadas($_SESSION["cantRespuestasContestadas"]);
            $this->model->cantRespuestasCorrectas($_SESSION["cantRespuestasCorrectas"]);
            $this->calcularDificultad();

            $this->model->calcularDificultadPregunta($pregunta);

            $this->presenter->render("views/resumenPartida.mustache", ["puntaje" => $_SESSION["puntaje"], "categoria" => $_SESSION["categoria"]]);
            unset($_SESSION["puntaje"]);
            unset($_SESSION["cantRespuestasContestadas"]);
            unset($_SESSION["cantRespuestasCorrectas"]);
        }
        exit();
    }


    public function timeLeft(){
        try {
            echo json_encode(["time_left" => $this->getTimeLeft()]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    private function getTimeLeft(){
        if (!isset($_SESSION["start_time"]))
            return 0;

        $duration = 30;
        $elapsed = time() - $_SESSION["start_time"];
        return max($duration - $elapsed, 0);
    }

    private function guardarPuntajeFinal(){
        if (isset($_SESSION['idUsuario']) && isset($_SESSION["puntaje"])) {
            $idUsuario = $_SESSION["idUsuario"];
            $puntaje = $_SESSION["puntaje"];
            $this->model->guardarPartidaEnBD($idUsuario, $puntaje);
            $this->model->guardarPuntajeMaximoEnBD($idUsuario, $puntaje);
        }
    }

    private function calcularDificultad(){
        $respuestasTotales = $this->model->obtenerCantTotalRespuestasRespondidas();
        $cantRespuestasCorrectas = $this->model->obtenerCantRespuestasCorrectas();
        $cantCorrectasBloque = $_SESSION["correctasBloque"] ?? 0;

        // Evaluar para respuestas totales entre 8 y 12
        if ($respuestasTotales >= 8 && $respuestasTotales <= 12) {
            $this->calcular($cantRespuestasCorrectas, $respuestasTotales);
        } else if ($respuestasTotales > 12) {
            $this->calcular($cantRespuestasCorrectas, $respuestasTotales);
        }
    }

    private function calcular($cantRespuestasCorrectas, $respuestasTotales){
        $porcentajeCorrectas = ($cantRespuestasCorrectas / $respuestasTotales) * 100;

        if ($porcentajeCorrectas <= 30) {
            $this->model->actualizarDificultad("basico");
        } else if ($porcentajeCorrectas <= 60) {
            $this->model->actualizarDificultad("intermedio");
        } else {
            $this->model->actualizarDificultad("avanzado");
        }
    }

}