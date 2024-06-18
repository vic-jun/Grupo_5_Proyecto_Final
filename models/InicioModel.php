<?php

class InicioModel
{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function obtenerDatosUsuario(){
        $usuario = $_SESSION['idUsuario'];
        $sql = "SELECT * FROM usuario WHERE id = '$usuario'";
        return $this->baseDeDatos->query($sql);
    }

}