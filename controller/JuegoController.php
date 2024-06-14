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

        // Si el tiempo se agotó, resetea el temporizador
        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
            unset($_SESSION["start_time"]);
        }

        // Inicia una nueva partida o selecciona una categoría
        if (!isset($_SESSION["categoria"]) || isset($_POST["categoria"])) {
            $_SESSION["categoria"] = $_POST["categoria"];
        }

        // Reinicia el temporizador para cada nueva pregunta
        $_SESSION["start_time"] = time();

        $res = $this->model->iniciarPartida($_SESSION["categoria"]);
        $res["time_left"] = $this->getTimeLeft();

        $this->presenter->render("views/juego.mustache", $res);
    }

    public function verificar()
    {
        session_start();

        if (!isset($_SESSION["start_time"])) {
            header("Location: /inicio");
            exit();
        }

        $respuesta = $_POST["respuesta"];
        $correcta = $_POST["correcta"];

        if ($this->getTimeLeft() <= 0) {
            header("Location: /inicio?timeout=true");
            exit();
        }

        $resultado = $this->model->verificarRespuesta($respuesta, $correcta);

        if ($resultado) {
            // No unsets start_time here because partida() will reset it anyway
            header("Location: /Juego/partida");
        } else {
            header("Location: /Inicio");
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

        $duration = 30; // Duración del temporizador en segundos
        $elapsed = time() - $_SESSION["start_time"];
        return max($duration - $elapsed, 0);
    }
}
