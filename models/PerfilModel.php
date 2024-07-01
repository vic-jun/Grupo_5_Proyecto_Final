<?php

require 'vendor/phpqrcode/qrlib.php';

class PerfilModel{
    private $baseDeDatos;
    public function __construct($db){
        $this->baseDeDatos = $db;
    }

    public function getPerfil($id){
        $sql = "SELECT * FROM usuario WHERE id = '$id'";
        $result = $this->baseDeDatos->query($sql);
        $data = 'localhost/perfil?usuario=' . $result[0]["id"];
        $qrUrl = $this->generarQR($data);
        $result[0]['qrUrl'] = $qrUrl;
        return $result;
    }

    public function generarQR($data){
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/public/img/qr/';
        if (!file_exists($dir))
            mkdir($dir, 0777, true);

        $photoname = md5($data) . '.png';
        $filename = $dir . $photoname;
        QRcode::png($data, $filename);
        $filename = '/public/img/qr/' . $photoname;
        return $filename;
    }


}
