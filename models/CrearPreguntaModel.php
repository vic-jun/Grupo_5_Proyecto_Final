<?php

class CrearPreguntaModel{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function guardarPreguntasYrespuestas($pregunta, $inc1, $inc2, $inc3, $categoria, $correcta){
        if (isset($pregunta) && isset($inc1) && isset($inc2) && isset($inc3) && isset($categoria) && isset($correcta)) {
            $this->insertarPregunta($pregunta, $categoria);
            $this->insertarRespuestas ($inc1, $inc2, $inc3, $correcta);
            $this->asociarRespuestasConPregunta($pregunta, $inc1, $inc2, $inc3, $correcta);
        } else {
            echo "Error al guardar la pregunta";
        }
    }

    public function insertarPregunta($pregunta, $categoria){
        $dificultad = "easy";
        $sql = "INSERT INTO preguntas (descripcion, categoria, validacion, dificultad) VALUES ('$pregunta', '$categoria', 1, '$dificultad')";
        $this->baseDeDatos->query($sql);
    }

    public function insertarRespuestas($inc1, $inc2, $inc3, $correcta){
        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$inc1')";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$inc2')";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$inc3')";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$correcta')";
        $this->baseDeDatos->query($sql);
    }
   
    public function asociarRespuestasConPregunta($pregunta, $inc1, $inc2, $inc3, $correcta){
        $idPregunta = $this->buscarIdDePregunta($pregunta);
        $idRespuesta1 = $this->buscarIdDeRespuesta($inc1);
        $idRespuesta2 = $this->buscarIdDeRespuesta($inc2);
        $idRespuesta3 = $this->buscarIdDeRespuesta($inc3);
        $idRespuestaCorrecta = $this->buscarIdDeRespuesta($correcta);

        $sql = "INSERT INTO preguntas_respuestas (id_pregunta, id_respuesta, correcta) VALUES ('$idPregunta', '$idRespuesta1', 0)";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO preguntas_respuestas (id_pregunta, id_respuesta, correcta) VALUES ('$idPregunta', '$idRespuesta2', 0)";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO preguntas_respuestas (id_pregunta, id_respuesta, correcta) VALUES ('$idPregunta', '$idRespuesta3', 0)";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO preguntas_respuestas (id_pregunta, id_respuesta, correcta) VALUES ('$idPregunta', '$idRespuestaCorrecta', '$idRespuestaCorrecta')";
        $this->baseDeDatos->query($sql);
    }

    public function buscarIdDePregunta($pregunta){
        $sql = "SELECT id FROM preguntas WHERE descripcion = '$pregunta'";
        $resultado = $this->baseDeDatos->query($sql);
        if (!isset($resultado[0]))
            return 0;

        return $resultado[0]['id'];
    }

    public function buscarIdDeRespuesta($respuesta){
        $sql = "SELECT id FROM respuestas WHERE descripcion = '$respuesta'";
        $resultado = $this->baseDeDatos->query($sql);
        if (!isset($resultado[0]))
            return 0;

        return $resultado[0]['id'];
    }

}