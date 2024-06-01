<?php

class SeleccionarCategoriaModel{

    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function seleccionar($categoria){


//        $sql = "SELECT * FROM categoria WHERE nombre = '$categoria'";
//        $result = $this->baseDeDatos->query($sql);
//        if ($result){
//            return true;
//        } else {
//            return false;
//        }
        return true;
    }

}
