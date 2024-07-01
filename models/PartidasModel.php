<?php

class PartidasModel{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function getPartidas() {
        $sql = "SELECT fecha_partida, puntaje_obtenido FROM partida WHERE id_usuario = '$_SESSION[idUsuario]' ORDER BY fecha_partida DESC";
        return $this->baseDeDatos->query($sql);
    }

}