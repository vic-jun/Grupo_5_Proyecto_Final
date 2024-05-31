<?php
class LoginModel{

    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function validar($email, $password){
        $sql = "SELECT * FROM usuario WHERE email = '$email' AND password = '$password'";
        $result = $this->baseDeDatos->query($sql);
        $row = $result->fetch_assoc();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
}