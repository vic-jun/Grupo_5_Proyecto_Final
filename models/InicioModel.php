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

    public function obtenerRol()
    {
        $usuario = $_SESSION['idUsuario'];
        $sql = "SELECT rol FROM usuario WHERE id = '$usuario'";
        $result = $this->baseDeDatos->query($sql);
        return $result[0]['rol'];

    }

}