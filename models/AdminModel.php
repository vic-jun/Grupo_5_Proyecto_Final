<?php

class AdminModel
{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function buscarPreguntasAverificar()
    {
        $sql = "SELECT preguntas.id as pregunta_id, preguntas.descripcion as pregunta, respuestas.descripcion as respuesta, preguntas_respuestas.correcta as es_correcta
                FROM preguntas
                INNER JOIN preguntas_respuestas ON preguntas.id = preguntas_respuestas.id_pregunta
                INNER JOIN respuestas ON preguntas_respuestas.id_respuesta = respuestas.id
                WHERE preguntas.validacion = 0";
        return $this->devolverPreguntasYrespuestas($sql);
    }

    public function aprobarPregunta($pregunta_id)
    {
        $sql = "UPDATE preguntas SET validacion = 1 WHERE id = $pregunta_id";
        $this->baseDeDatos->query($sql);
    }

    public function rechazarPregunta($pregunta_id)
    {
        $sql = "DELETE preguntas_respuestas FROM preguntas_respuestas WHERE id_pregunta = $pregunta_id";
        $this->baseDeDatos->query($sql);

        $sql = "DELETE respuestas FROM respuestas
            INNER JOIN preguntas_respuestas ON respuestas.id = preguntas_respuestas.id_respuesta
            WHERE preguntas_respuestas.id_pregunta = $pregunta_id";
        $this->baseDeDatos->query($sql);

        $sql = "DELETE FROM preguntas WHERE id = $pregunta_id";
        $this->baseDeDatos->query($sql);
    }

    public function obtenerTodasLasPreguntasYrespuestas()
    {
        $sql = "SELECT preguntas.id as pregunta_id, preguntas.descripcion as pregunta, respuestas.descripcion as respuesta, preguntas_respuestas.correcta as es_correcta
                FROM preguntas
                INNER JOIN preguntas_respuestas ON preguntas.id = preguntas_respuestas.id_pregunta
                INNER JOIN respuestas ON preguntas_respuestas.id_respuesta = respuestas.id
                WHERE preguntas.validacion != 0";
        return $this->devolverPreguntasYrespuestas($sql);
    }

    public function devolverPreguntasYrespuestas(string $sql): array
    {
        $result = $this->baseDeDatos->queryParaVerificar($sql);

        $preguntas = [];
        while ($row = $result->fetch_assoc()) {
            $pregunta_id = $row['pregunta_id'];
            if (!isset($preguntas[$pregunta_id])) {
                $preguntas[$pregunta_id] = [
                    'pregunta_id' => $pregunta_id,
                    'pregunta' => $row['pregunta'],
                    'respuestas_correctas' => [],
                    'respuestas_incorrectas' => []
                ];
            }
            if ($row['es_correcta'] != 0) {
                $preguntas[$pregunta_id]['respuestas_correctas'][] = $row['respuesta'];
            } else {
                $preguntas[$pregunta_id]['respuestas_incorrectas'][] = $row['respuesta'];
            }
        }

        return array_values($preguntas);
    }

    public function modificar($pregunta_id, $pregunta, $respuestaIncorrecta1, $respuestaIncorrecta2, $respuestaIncorrecta3, $respuestaCorrecta)
    {
        $idRespuesta1 = $this->buscarIdRespuestaPorDescripcion($respuestaIncorrecta1);
        $idRespuesta2 = $this->buscarIdRespuestaPorDescripcion($respuestaIncorrecta2);
        $idRespuesta3 = $this->buscarIdRespuestaPorDescripcion($respuestaIncorrecta3);
        $idRespuesta4 = $this->buscarIdRespuestaPorDescripcion($respuestaCorrecta);

        $sql = "UPDATE preguntas SET descripcion = '$pregunta' WHERE id = $pregunta_id";
        $this->baseDeDatos->query($sql);

        $sql = "UPDATE respuestas SET descripcion = '$respuestaIncorrecta1' WHERE id = $idRespuesta1";
        $this->baseDeDatos->query($sql);

        $sql = "UPDATE respuestas SET descripcion = '$respuestaIncorrecta2' WHERE id = $idRespuesta2";
        $this->baseDeDatos->query($sql);

        $sql = "UPDATE respuestas SET descripcion = '$respuestaIncorrecta3' WHERE id = $idRespuesta3";
        $this->baseDeDatos->query($sql);

        $sql = "UPDATE respuestas SET descripcion = '$respuestaCorrecta' WHERE id = $idRespuesta4";
        $this->baseDeDatos->query($sql);


    }

    public function buscarPreguntaYrespuestaPorId($idPregunta){

        $resPreg = $this->buscarPreguntas($idPregunta);

        if (is_array($resPreg) && count($resPreg) > 0) {
            $pregunta = $resPreg;
            $id = $pregunta['id'];

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

    public function buscarPreguntas($id){
        $sql = "SELECT * FROM preguntas WHERE id = '$id'";

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


    private function buscarIdRespuestaPorDescripcion($respuestaIncorrecta)
    {
        $sql = "SELECT id FROM respuestas WHERE descripcion = '$respuestaIncorrecta'";
        $this->baseDeDatos->query($sql);
        return $this->baseDeDatos->fetch_assoc()['id'];

    }

}