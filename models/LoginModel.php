<?php
class LoginModel{

    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function validar($usuario, $password){
        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario' AND password = '$password' AND confirmed = 1";

        $result = $this->baseDeDatos->query($sql);

        if (empty($result)){
            return false;
        } else {
            return true;
        }
    }

    public function buscarIdUsuario ($usuario){
        $sql = "SELECT id FROM usuario WHERE nombre_de_usuario = '$usuario'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && isset($result[0]['id'])) {
            return $result[0]['id'];
        }
        return null;
    }

    public function buscarRolUsuario ($usuario){
        $sql = "SELECT rol FROM usuario WHERE nombre_de_usuario = '$usuario'";

        $result = $this->baseDeDatos->query($sql);

        if (is_array($result) && isset($result[0]['rol'])) {
            return $result[0]['rol'];
        }
        return null;
    }

}