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

}