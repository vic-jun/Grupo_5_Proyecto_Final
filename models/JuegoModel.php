<?php

class JuegoModel{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function iniciarPartida($categoria){

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

    public function traerPreguntaEspecifica($id, $categoria){
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

    public function buscarPreguntaPorID($id){
        $sql = "SELECT * FROM preguntas WHERE id = '$id'";

        $respuesta = $this->baseDeDatos->query($sql);

        return $respuesta;
    }

    public function buscarPreguntas($categoria){
        /*
        $sql = "SELECT * FROM preguntas WHERE categoria = '$categoria'";
        $result = $this->baseDeDatos->query($sql);*/

        $nivelUsuario = $this->obtenerNivelUsuario();

        $result = null;

        if ($nivelUsuario === "basico") {
            $sql1 = "SELECT * FROM preguntas WHERE categoria = '$categoria' AND dificultad = 'easy'";
            $result = $this->baseDeDatos->query($sql1);
        } elseif ($nivelUsuario === "intermedio") {
            $sql2 = "SELECT * FROM preguntas WHERE categoria = '$categoria' AND dificultad = 'intermidiate'";
            $result = $this->baseDeDatos->query($sql2);
        } elseif ($nivelUsuario === "avanzado") {
            $sql3 = "SELECT * FROM preguntas WHERE categoria = '$categoria' AND dificultad = 'difficult'";
            $result = $this->baseDeDatos->query($sql3);
        }

        $i = 0;
        while ($i < count($result)) {
            if($this->verificarVerTodasLasPreguntasDeUnaCategoria($categoria)){
                $this->borrarPreguntasRespondidas($categoria);
            }
            if($this->buscarPreguntasRespondidas($result[$i]['id'])){
                unset($result[$i]);
                $result = array_values($result);
            } else {
                $i++;
            }
        }

        if (is_array($result) && count($result) > 0) {
            $preguntas = $result;

            while (count($preguntas) > 0) {

                $index = array_rand($preguntas);
                $pregunta = $preguntas[$index];

            $preguntasUsuario= $this->buscarPreguntasRespondidas($pregunta['id']);

            if(!$preguntasUsuario){
                $this->insertarPreguntaUsuario($pregunta['id']);
                unset($preguntas[$index]);
                $preguntas = array_values($preguntas);
                break;
            }elseif (is_array($preguntasUsuario)){

                if($this->verificarVerTodasLasPreguntasDeUnaCategoria($categoria)){
                    $this->borrarPreguntasRespondidas($categoria);
                }

                if ($preguntasUsuario['idPregunta'] != $pregunta['id']) {
                        $this->insertarPreguntaUsuario($pregunta['id']);
                        unset($preguntas[$index]);
                    $preguntas = array_values($preguntas);
                        break;
                }else{
                    unset($preguntas[$index]);
                }
                $preguntas = array_values($preguntas);
                }
            }
            return $pregunta;
        } else {
            $sql3 = "SELECT * FROM preguntas WHERE categoria = '$categoria'";
            $preguntas = $this->baseDeDatos->query($sql3);
            $i = 0;
            while ($i < count($preguntas)) {
                if($this->verificarVerTodasLasPreguntasDeUnaCategoria($categoria)){
                    $this->borrarPreguntasRespondidas($categoria);
                }
                if($this->buscarPreguntasRespondidas($preguntas[$i]['id'])){
                    unset($preguntas[$i]);
                    $preguntas = array_values($preguntas);
                } else {
                    $i++;
                }
            }

            while (count($preguntas) > 0) {

                $index = array_rand($preguntas);
                $pregunta = $preguntas[$index];

                $preguntasUsuario= $this->buscarPreguntasRespondidas($pregunta['id']);

                if(!$preguntasUsuario){
                    $this->insertarPreguntaUsuario($pregunta['id']);
                    unset($preguntas[$index]);
                    $preguntas = array_values($preguntas);
                    break;
                }elseif (is_array($preguntasUsuario)){

                    if($this->verificarVerTodasLasPreguntasDeUnaCategoria($categoria)){
                        $this->borrarPreguntasRespondidas($categoria);
                    }

                    if ($preguntasUsuario['idPregunta'] != $pregunta['id']) {
                        $this->insertarPreguntaUsuario($pregunta['id']);
                        unset($preguntas[$index]);
                        $preguntas = array_values($preguntas);
                        break;
                    }else{
                        unset($preguntas[$index]);
                    }
                    $preguntas = array_values($preguntas);
                }
            }
            return $pregunta;
        }
    }

    public function buscarPreguntasRespondidas($id){
        $sql = "SELECT * FROM preguntas_usuarios WHERE idPregunta = '$id' AND idUsuario = '$_SESSION[idUsuario]'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function insertarPreguntaUsuario($id){

        $sql = "INSERT INTO preguntas_usuarios (idUsuario, idPregunta) VALUES ('$_SESSION[idUsuario]','$id')";
        $this->baseDeDatos->query($sql);
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

        $sql = "SELECT puntaje FROM usuario WHERE id = '$idUsuario'";
        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && isset($result[0]['puntaje'])) {
            if (is_numeric($result[0]['puntaje']) && $result[0]['puntaje'] < $puntaje) {
                $sql2 = "UPDATE usuario SET puntaje = '$puntaje' WHERE id = '$idUsuario'";
                $this->baseDeDatos->query($sql2);
            }
        }
    }

    public function guardarPartidaEnBD($idUsuario, $puntaje){
        $sql = "INSERT INTO partida (puntaje_obtenido, fecha_partida, id_usuario) VALUES ('$puntaje', NOW(), '$idUsuario')";
        $this->baseDeDatos->query($sql);
    }

    public function reportarPregunta($pregunta){
        $sql = "UPDATE preguntas SET reportada = 1 WHERE descripcion = '$pregunta'";
        $this->baseDeDatos->query($sql);
    }

    public function cantRespuestasContestadas($cantidad){
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

    public function cantRespuestasCorrectas($cantidad){
        $sql = "SELECT cntRespuestasCorrectas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        $respuestasActuales = $result[0]['cntRespuestasCorrectas'];

        $nuevaCantidad = $respuestasActuales + $cantidad;

        $sql = "UPDATE usuario SET cntRespuestasCorrectas = '$nuevaCantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    public function cantCorrectasBloque($cantidad){
        $sql = "SELECT correctasBloque FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        $respuestasActuales = $result[0]['correctasBloque'];

        $nuevaCantidad = $respuestasActuales + $cantidad;

        $sql = "UPDATE usuario SET correctasBloque = '$nuevaCantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }
    public function actualizarCorrectasBloque($cantidad){
        $sql = "UPDATE usuario SET correctasBloque = '$cantidad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    public function obtenerCantTotalRespuestasRespondidas(){
        $sql = "SELECT cantRespuestasRespondidas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['cantRespuestasRespondidas'];
    }

    public function obtenerCantRespuestasCorrectas(){
        $sql = "SELECT cntRespuestasCorrectas FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['cntRespuestasCorrectas'];

    }

    public function obtenerCantCorrectasBloque(){
        $sql = "SELECT correctasBloque FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['correctasBloque'];
    }

    public function actualizarDificultad($dificultad){
        $sql = "UPDATE usuario SET nivelUsuario = '$dificultad' WHERE id = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    private function verificarVerTodasLasPreguntasDeUnaCategoria($categoria)
    {

        $sql1 = "SELECT * FROM preguntas_usuarios pu JOIN preguntas p ON p.id = pu.idPregunta WHERE p.categoria = '$categoria' AND pu.idUsuario = '$_SESSION[idUsuario]'";

        $preguntas = $this->baseDeDatos->query($sql1);

        $sql2 = "SELECT * FROM preguntas WHERE categoria = '$categoria'";

        $todasLasPreguntas = $this->baseDeDatos->query($sql2);

        if(count($preguntas) == count($todasLasPreguntas)){
            return true;
        }else{
            return false;
        }
    }

    private function borrarPreguntasRespondidas($categoria){
        $sql = "DELETE FROM preguntas_usuarios 
        WHERE idPregunta IN (
            SELECT id FROM preguntas WHERE categoria = '$categoria'
        ) 
        AND idUsuario = '$_SESSION[idUsuario]'";
        $this->baseDeDatos->query($sql);
    }

    private function obtenerCantPreguntasCorrectas($pregunta){
        $sql = "SELECT cantidadCorrectas FROM preguntas WHERE descripcion = '$pregunta'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['cantidadCorrectas'];
    }

    private function obtenerCantPreguntasIncorrectas($pregunta){
        $sql = "SELECT cantidadIncorrectas FROM preguntas WHERE descripcion = '$pregunta'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['cantidadIncorrectas'];
    }
    public function actualizarCantidadCorrectas($pregunta){

        $respuestasActuales = $this->obtenerCantPreguntasCorrectas($pregunta);

        $nuevaCantidad = $respuestasActuales + 1;

        $sql = "UPDATE preguntas SET cantidadCorrectas = '$nuevaCantidad' WHERE descripcion = '$pregunta'";
        $this->baseDeDatos->query($sql);
    }

    public function actualizarCantidadIncorrectas($pregunta){

        $respuestasActuales = $this->obtenerCantPreguntasIncorrectas($pregunta);

        $nuevaCantidad = $respuestasActuales + 1;

        $sql = "UPDATE preguntas SET cantidadIncorrectas = '$nuevaCantidad' WHERE descripcion = '$pregunta'";
        $this->baseDeDatos->query($sql);
    }

    public function calcularDificultadPregunta($pregunta)
    {
        $correctas = $this->obtenerCantPreguntasCorrectas($pregunta);

        $incorrectas = $this->obtenerCantPreguntasIncorrectas($pregunta);

        $totales = $correctas + $incorrectas;

        $porcentajeCorrectas = ($correctas / $totales) * 100;

        if ($porcentajeCorrectas >= 0 && $porcentajeCorrectas <= 30) {
            $sql = "UPDATE preguntas SET dificultad = 'difficult' WHERE descripcion = '$pregunta'";
            $this->baseDeDatos->query($sql);
        } else if ($porcentajeCorrectas > 30 && $porcentajeCorrectas < 60) {
            $sql = "UPDATE preguntas SET dificultad = 'intermediate' WHERE descripcion = '$pregunta'";
            $this->baseDeDatos->query($sql);
        } else if($porcentajeCorrectas >= 60 && $porcentajeCorrectas <= 100) {
            $sql = "UPDATE preguntas SET dificultad = 'easy' WHERE descripcion = '$pregunta'";
            $this->baseDeDatos->query($sql);
        }


    }

    private function obtenerNivelUsuario()
    {
        $sql = "SELECT nivelUsuario FROM usuario WHERE id = '$_SESSION[idUsuario]'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['nivelUsuario'];
    }
}