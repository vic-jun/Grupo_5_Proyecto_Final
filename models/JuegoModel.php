<?php

class JuegoModel
{

    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function iniciarPartida($categoria){

        $resPreg = $this->buscarPreguntas($categoria);

        if (is_array($resPreg) && count($resPreg) > 0) {
            $pregunta = $resPreg;
            $id = $pregunta['id'];
            $pregunta = $pregunta['descripcion'];
            $respuestas = $this->buscarRespuestas($id);
            $correcta = 0;

            if (is_array($respuestas) && count($respuestas) > 0) {

                for ($i = 0; $i < count($respuestas); $i++) {
                    if ($respuestas[$i]["correcta"] != 0) {
                        $correcta = $respuestas[$i]["id_respuesta"];
                        break;
                    }
                }

                return array('pregunta' => $pregunta, 'respuestas' => $respuestas, "correcta" => $correcta);
            } else {
                return $respuestas;
            }

        } else {
            return $resPreg;
        }
    }

    public function buscarPreguntas($categoria){
        $sql = "SELECT * FROM preguntas WHERE categoria = '$categoria'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && count($result) > 0) {
            $preguntas = $result;
            $pregunta = $preguntas[array_rand($preguntas)];
            return $pregunta;
        } else {
            return "No hay preguntas en la base de datos";
        }
    }

    public function buscarRespuestas($id){
        $sql = "SELECT PR.id_respuesta, R.descripcion as descripcion, PR.correcta FROM preguntas_respuestas PR JOIN respuestas R ON R.id = PR.id_respuesta WHERE id_pregunta = '$id'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && count($result) > 0) {
            $respuestas = $result;
            return $respuestas;
        } else {
            return "No hay respuestas en la base de datos";
        }
    }

    public function verificarRespuesta($respuesta, $correcta){
        if ($respuesta == $correcta) {
            return true;
        } else {
            return false;
        }
    }

    public function generarPuntaje($pregunta){
        if ($pregunta != null) {
            $sql = "SELECT dificultad FROM preguntas WHERE descripcion = '$pregunta'";
            $result = $this->baseDeDatos->query($sql);
            $puntaje = 0;

            if ($result != null) {
                $dificultad = $result[0]['dificultad'];

                if ($dificultad == "easy") {
                    $puntaje = 10;
                } else if ($dificultad == "intermediate") {
                    $puntaje = 15;
                } else if ($dificultad == "difficult") {
                    $puntaje = 20;
                }
            }
            return $puntaje;
        } else {
            return "No se ha encontrado la pregunta";
        }
    }

    public function guardarPuntajeMaximoEnBD($idUsuario, $puntaje){
        $sql = "SELECT puntaje FROM usuario WHERE id = :idUsuario";
        $stmt = $this->baseDeDatos->prepare($sql);
        $stmt->execute([':idUsuario' => $idUsuario]);
        $result = $stmt->fetch();

        if ($result && isset($result['puntaje'])) {
            if (is_numeric($result['puntaje']) && $result['puntaje'] < $puntaje) {
                $sql2 = "UPDATE usuario SET puntaje = :puntaje WHERE id = :idUsuario";
                $stmt2 = $this->baseDeDatos->prepare($sql2);
                $stmt2->execute([':puntaje' => $puntaje, ':idUsuario' => $idUsuario]);

                if($stmt2->errorInfo()[2]) {
                    echo "Hubo un error al actualizar el puntaje: " . $stmt2->errorInfo()[2];
                }
            }
        } else {
            echo "No se encontró al usuario o hubo otro error: " . $stmt->errorInfo()[2];
        }
    }

}