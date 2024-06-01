<?php
class LoginModel{

    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function validar($usuario, $password){
        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario' AND password = '$password'";
        $result = $this->baseDeDatos->query($sql);
        if ($result){
            return true;
        } else {
            return false;
        }
    }
}