<?php

class JuegoController
{
    public $model;
    public $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function partida()
    {
        session_start();

        if (isset($_SESSION['preguntaID'])) {
            $res = $this->model->traerPreguntaEspecifica($_SESSION['preguntaID'], $_SESSION["categoria"]);
            $_SESSION["cantRespuestasContestadas"] = 0;
            $_SESSION["cantRespuestasCorrectas"] = 0;
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

    public function verificar()
    {
        session_start();

        unset($_SESSION['preguntaID']);

        if (!isset($_SESSION["start_time"])) {
            header("Location: /inicio");
            exit();
        }

        $accion = $_POST["accion"];
        $pregunta = $_POST["pregunta"];

        if ($accion == "reportar") {
            $this->model->reportarPregunta($pregunta);
            header("Location: /Juego/partida");
            exit();
        }

        $respuesta = $_POST["respuesta"];
        $correcta = $_POST["correcta"];

        if ($this->getTimeLeft() <= 0) {
            header("Location: /inicio?timeout=true");
            exit();
        }

        if (!isset($_SESSION["cantRespuestasContestadas"])) {
            $_SESSION["cantRespuestasContestadas"] = 0;
        }

        if (!isset($_SESSION["cantRespuestasCorrectas"])) {
            $_SESSION["cantRespuestasCorrectas"] = 0;
        }

        $resultado = $this->model->verificarRespuesta($respuesta, $correcta);

        if ($resultado) {
            $puntaje = $this->model->generarPuntaje($pregunta);
            $_SESSION["puntaje"] += $puntaje;
            $_SESSION["cantRespuestasContestadas"]++;
            $_SESSION["cantRespuestasCorrectas"]++;
            header("Location: /Juego/partida");
        } else {
            $this->guardarPuntajeFinal();
            $this->model->cantRespuestasContestadas($_SESSION["cantRespuestasContestadas"]);
            $this->model->cantRespuestasCorrectas($_SESSION["cantRespuestasCorrectas"]);
            $this->calcularDificultad();
            $this->presenter->render("views/resumenPartida.mustache", ["puntaje" => $_SESSION["puntaje"], "categoria" => $_SESSION["categoria"]]);
            unset($_SESSION["puntaje"]);
            unset($_SESSION["cantRespuestasContestadas"]);
            unset($_SESSION["cantRespuestasCorrectas"]);
        }
        exit();
    }

    public function timeLeft()
    {
        session_start();
        echo json_encode(["time_left" => $this->getTimeLeft()]);
    }

    private function getTimeLeft()
    {
        if (!isset($_SESSION["start_time"])) {
            return 0;
        }

        $duration = 30;
        $elapsed = time() - $_SESSION["start_time"];
        return max($duration - $elapsed, 0);
    }

    private function guardarPuntajeFinal()
    {
        if (isset($_SESSION['idUsuario']) && isset($_SESSION["puntaje"])) {

            $idUsuario = $_SESSION["idUsuario"];
            $puntaje = $_SESSION["puntaje"];
            $this->model->guardarPartidaEnBD($idUsuario, $puntaje);
            $this->model->guardarPuntajeMaximoEnBD($idUsuario, $puntaje);
        }
    }

    private function calcularDificultad()
    {
        $respuestasTotales = $this->model->obtenerCantTotalRespuestasRespondidas();
        $cantRespuestasCorrectas = $this->model->obtenerCantRespuestasCorrectas();
        if ($respuestasTotales > 10 && $respuestasTotales <= 15){
            if ($cantRespuestasCorrectas <= 3) {
                $this->model->actualizarDificultad("basico");
            } else if ($cantRespuestasCorrectas <= 6) {
                $this->model->actualizarDificultad("intermedio");
            } else if ($cantRespuestasCorrectas <= 10) {
                $this->model->actualizarDificultad("avanzado");
            }
        } else {
            echo "basklsdad";
        }


    }

}
