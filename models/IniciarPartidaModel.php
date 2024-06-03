<?php

class IniciarPartidaModel{

    private $baseDeDatos;
    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function iniciarPartida($categoria){
       $resPreg = $this->buscarPreguntas($categoria);

       if(is_array($resPreg) && count($resPreg) > 0){
           $pregunta = $resPreg;
           $id = $pregunta['id'];
           $pregunta = $pregunta['descripcion'];
           $respuestas = $this->buscarRespuestas($id);
           $correcta = 0;

           if(is_array($respuestas) && count($respuestas) > 0){

               for ($i=0; $i < count($respuestas); $i++) {
                   if($respuestas[$i]["correcta"] != 0){
                       $correcta = $respuestas[$i]["id_respuesta"];
                       break;
                   }
               }
               $descripciones = array_map(function($respuesta) {
                   return $respuesta['descripcion'];
               }, $respuestas);
               return array('pregunta' => $pregunta, 'respuestas' => $descripciones, "correcta" => $correcta);
           }else{
               return $respuestas;
           }

       } else {
           return $resPreg;
       }
    }

    public function buscarPreguntas($categoria){
        $sql = "SELECT * FROM preguntas WHERE categoria = '$categoria'";

        $result = $this->baseDeDatos->query($sql);

        if(is_array($result) && count($result) > 0){
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

        if(is_array($result) && count($result) > 0){
            $respuestas = $result;
            return $respuestas;
        } else {
            return "No hay respuestas en la base de datos";
        }
    }

    public function verificarRespuesta($respuesta, $correcta, $categoria){
        $sql = "SELECT R.id FROM respuestas R JOIN preguntas_respuestas PR ON R.id = PR.id_respuesta WHERE R.descripcion = '$respuesta'";

        $result = $this->baseDeDatos->query($sql);

        if(is_array($result) && count($result) > 0){
            $respuesta = $result[0];
            if($respuesta['id'] == $correcta){
                return $categoria;
            } else {
                return false;
            }
        } else {
            return "No se encontró la respuesta";
        }
    }
}