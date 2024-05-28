<?php

class ConfirmarEmailModel
{

    public function __construct()
    {
    }

    public function confirmarEmail($email, $token)
    {
        $hash = $_GET['hash'];

        if($this->buscarHash($hash)) {

            echo "Email confirmado";

        }else {
            echo "Email no confirmado";
        }
    }

    public function buscarHash($hash) {
        // Leer el archivo en un array
        $hashes = file('hashes.txt', FILE_IGNORE_NEW_LINES);

        // Verificar si el hash está en el array
        if (in_array($hash, $hashes)) {
            return true;
        } else {
            return false;
        }
    }
}