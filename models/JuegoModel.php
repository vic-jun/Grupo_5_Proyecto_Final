<?php

class JuegoModel
{

    private $baseDeDatos;

    public function __construct($baseDeDatos)
    {
        $this->baseDeDatos = $baseDeDatos;
    }

    public function iniciarPartida($categoria)
    {

        $resPreg = $this->buscarPreguntas($categoria);

        if (is_array($resPreg) && count($resPreg) > 0) {
            $pregunta = $resPreg;
            $id = $pregunta['id'];

            $_SESSION['preguntaID'] = $id;

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

                return array('pregunta' => $pregunta, 'respuestas' => $respuestas, "correcta" => $correcta, "categoria" => $categoria);
            } else {
                return $respuestas;
            }

        } else {
            return $resPreg;
        }
    }

    public function traerPreguntaEspecifica($id, $categoria)
    {

        $pregunta = $this->buscarPreguntaPorID($id);

        $respuestas = $this->buscarRespuestas($id);

        if (is_array($respuestas) && count($respuestas) > 0) {

            for ($i = 0; $i < count($respuestas); $i++) {
                if ($respuestas[$i]["correcta"] != 0) {
                    $correcta = $respuestas[$i]["id_respuesta"];
                    break;
                }
            }
            $pregunta = $pregunta[0]['descripcion'];
            return array('pregunta' => $pregunta, 'respuestas' => $respuestas, "correcta" => $correcta, "categoria" => $categoria);
        } else {
            return $respuestas;
        }
    }

    public function buscarPreguntaPorID($id)
    {
        $sql = "SELECT * FROM preguntas WHERE id = '$id'";

        $respuesta = $this->baseDeDatos->query($sql);

        return $respuesta;
    }

    public function buscarEnJson($idPregunta){

        $json = $this->traerJson($_SESSION['idUsuario']);

        if($json == null){
            $this->crearJson($idPregunta);
            return 0;
        }else{
            $json = json_decode($json);
            if(in_array($idPregunta, $json)){
                return true;
            }else{
                return false;
            }
        }

    }

    public function traerJson($idUsuario){
        $sql = "SELECT respuestasVistas FROM usuario WHERE id = '$idUsuario'";
        $result = $this->baseDeDatos->query($sql);

        if($result[0]['respuestasVistas'] != null){
            return $result[0]['respuestasVistas'];
        }else{
        return null;}
    }

    public function crearJson($idPregunta){
        $json = array();
        array_push($json, $idPregunta);
        $json = json_encode($json);
        $sql = "UPDATE usuario SET respuestasVistas = '$json' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    public function actualizarJson($idPregunta){
        $json = $this->traerJson($_SESSION['idUsuario']);
        $json = json_decode($json);
        array_push($json, $idPregunta);
        $json = json_encode($json);
        $sql = "UPDATE usuario SET respuestasVistas = '$json' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }


    public function buscarPreguntas($categoria)
    {
        $sql = "SELECT * FROM preguntas WHERE categoria = '$categoria'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && count($result) > 0) {
            $preguntas = $result;
            $pregunta = $preguntas[array_rand($preguntas)];

            if ($this->buscarEnJson($pregunta['id'])){

                $totalPreg = $this->traerJson($_SESSION['idUsuario']);
                $totalPreg = json_decode($totalPreg);

                $val= 0;
                for ($i=0; $i < count($preguntas); $i++) {
                    $resultado = $this->buscarPreguntaPorID($totalPreg[$i]);
                    if($resultado[0]['categoria'] == $categoria){
                        if($resultado[0]['id'] == $totalPreg[$i]){
                            $val++;
                        }
                    }
                }
                if($val == count($preguntas)) {
                    $sql = "UPDATE usuario SET respuestasVistas = NULL WHERE id = '$_SESSION[idUsuario]'";
                    $this->baseDeDatos->query($sql);
                }
                $pregunta = $this->buscarPreguntas($categoria);

            }else if(!$this->buscarEnJson($pregunta['id'])){
                $this->actualizarJson($pregunta['id']);
            }

            return $pregunta;
        } else {
            return "No hay preguntas en la base de datos";
        }
    }

    public function buscarRespuestas($id)
    {
        $sql = "SELECT PR.id_respuesta, R.descripcion as descripcion, PR.correcta FROM preguntas_respuestas PR JOIN respuestas R ON R.id = PR.id_respuesta WHERE id_pregunta = '$id'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && count($result) > 0) {
            $respuestas = $result;
            return $respuestas;
        } else {
            return "No hay respuestas en la base de datos";
        }
    }

    public function verificarRespuesta($respuesta, $correcta)
    {
        if ($respuesta == $correcta) {
            return true;
        } else {
            return false;
        }
    }

    public function generarPuntaje($pregunta)
    {
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

    public function guardarPuntajeMaximoEnBD($idUsuario, $puntaje)
    {

        $sql = "SELECT puntaje FROM usuario WHERE id = '$idUsuario'";
        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && isset($result[0]['puntaje'])) {
            if (is_numeric($result[0]['puntaje']) && $result[0]['puntaje'] < $puntaje) {
                $sql2 = "UPDATE usuario SET puntaje = '$puntaje' WHERE id = '$idUsuario'";
                $this->baseDeDatos->query($sql2);
            }
        }
    }

    public function guardarPartidaEnBD($idUsuario, $puntaje)
    {
        $sql = "INSERT INTO partida (puntaje_obtenido, fecha_partida, id_usuario) VALUES ('$puntaje', NOW(), '$idUsuario')";
        $this->baseDeDatos->query($sql);
    }

    public function reportarPregunta($pregunta)
    {
        $sql = "UPDATE preguntas SET reportada = 1 WHERE descripcion = '$pregunta'";
        $this->baseDeDatos->query($sql);
    }

    public function cantRespuestasContestadas($cantidad)
    {
        $cantidad = $cantidad + 1;
        // Obtener el valor actual de cantRespuestasRespondidas
        $sql = "SELECT cantRespuestasRespondidas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        $respuestasActuales = $result[0]['cantRespuestasRespondidas'];

        // Sumar la cantidad que viene por parámetro
        $nuevaCantidad = $respuestasActuales + $cantidad;

        // Actualizar el valor en la base de datos
        $sql = "UPDATE usuario SET cantRespuestasRespondidas = '$nuevaCantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    public function cantRespuestasCorrectas($cantidad)
    {
        $sql = "SELECT cntRespuestasCorrectas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        $respuestasActuales = $result[0]['cntRespuestasCorrectas'];

        $nuevaCantidad = $respuestasActuales + $cantidad;

        $sql = "UPDATE usuario SET cntRespuestasCorrectas = '$nuevaCantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    public function cantCorrectasBloque($cantidad)
    {
        $sql = "SELECT correctasBloque FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        $respuestasActuales = $result[0]['correctasBloque'];

        $nuevaCantidad = $respuestasActuales + $cantidad;

        $sql = "UPDATE usuario SET correctasBloque = '$nuevaCantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }
    public function actualizarCorrectasBloque($cantidad)
    {
        $sql = "UPDATE usuario SET correctasBloque = '$cantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    public function obtenerCantTotalRespuestasRespondidas()
    {
        $sql = "SELECT cantRespuestasRespondidas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['cantRespuestasRespondidas'];
    }

    public function obtenerCantRespuestasCorrectas()
    {
        $sql = "SELECT cntRespuestasCorrectas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['cntRespuestasCorrectas'];

    }

    public function obtenerCantCorrectasBloque()
    {
        $sql = "SELECT correctasBloque FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['correctasBloque'];
    }

    public function actualizarDificultad($dificultad)
    {
        $sql = "UPDATE usuario SET nivelUsuario = '$dificultad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

}