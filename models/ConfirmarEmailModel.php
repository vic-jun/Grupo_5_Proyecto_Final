<?php

class ConfirmarEmailModel{
    private $resultado;
    public function __construct(){
    }

    public function confirmarEmail($email, $token){
        if($this->buscarHash($token)) {

            $this->resultado = "Email confirmado";
            return $this->resultado;

        }else {
            $this->resultado =  "Email no confirmado";
            return $this->resultado;
        }
    }

    public function buscarHash($hash) {
        $hashes = file('hashes.txt', FILE_IGNORE_NEW_LINES);

        if (in_array($hash, $hashes)) {
            return true;
        } else {
            return false;
        }
    }
}