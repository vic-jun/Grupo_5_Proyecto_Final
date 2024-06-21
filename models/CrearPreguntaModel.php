<?php

class CrearPreguntaModel
{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function guardarPreguntasYrespuestas($pregunta, $inc1, $inc2, $inc3, $categoria, $correcta)
    {
        $this->insertarPregunta($pregunta, $categoria);
        $this-> insertarRespuestas ($inc1, $inc2, $inc3, $correcta);

    }

    public function insertarPregunta($pregunta, $categoria)
    {
        $dificultad = "easy";
        $sql = "INSERT INTO preguntas (descripcion, categoria, validacion, dificultad) VALUES ('$pregunta', '$categoria', 1, '$dificultad')";
        $this->baseDeDatos->query($sql);
    }

    public function insertarRespuestas($inc1, $inc2, $inc3, $correcta)
    {

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$inc1')";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$inc2')";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$inc3')";
        $this->baseDeDatos->query($sql);

        $sql = "INSERT INTO respuestas (descripcion) VALUES ('$correcta')";
        $this->baseDeDatos->query($sql);

    }
   


}