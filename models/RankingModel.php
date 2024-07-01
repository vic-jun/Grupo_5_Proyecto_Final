<?php

class RankingModel{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function getRanking() {
        $sql = "SELECT nombre_de_usuario, puntaje, id FROM usuario WHERE rol = 'usuario'  ORDER BY puntaje DESC";
        return $this->baseDeDatos->query($sql);
    }

}
