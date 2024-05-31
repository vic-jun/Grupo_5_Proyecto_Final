<?php

require_once "vendor/PHPMailer-6.9.1/src/PHPMailer.php";

class RegistrarModel{
    private $baseDeDatos;

    public function __construct($baseDeDatos){
        $this->baseDeDatos = $baseDeDatos;
    }

    public function registrarse($nombreUsuario, $nombre, $apellido, $email, $password, $pais, $ciudad, $fotoDePerfil, $añoNacimiento, $genero){

        $rol = "usuario";
        $nombre_foto = "";
        $fotoPerfil = null;
        if (isset($fotoDePerfil)) {
            $errors = array();
            $file_name = $fotoDePerfil['name'];
            $file_size = $fotoDePerfil['size'];
            $file_tmp = $fotoDePerfil['tmp_name'];
            $file_type = $fotoDePerfil['type'];

            $extensions = array("jpeg", "jpg", "png");

            if ($file_size > 2097152) {
                $errors[] = 'File size must be less than 2 MB';
            }

            if (empty($errors) == true) {
                $fotoPerfil = $fotoDePerfil;
                $archivo_temporal = $fotoDePerfil['tmp_name'];
                $directorio_destino = '/public/img';
                $nombre_foto = $directorio_destino . $fotoPerfil;
                move_uploaded_file($archivo_temporal, $directorio_destino . $fotoPerfil);
            } else {
                print_r($errors);
            }
        }

        $sql = "INSERT INTO usuario (nombre_de_usuario,nombre,apellido,email,password,pais,ciudad,año_nacimiento,genero,rol, foto) VALUES ($nombreUsuario,$nombre,$apellido,$email,$password,$pais,$ciudad,$añoNacimiento,$genero,$rol,$nombre_foto)";

        include_once ("helper/Logger.php");
        $log = new Logger();
        $log->info($sql);

        $this->baseDeDatos->query($sql);


        $hash = hash('sha256', $email . $password);

        file_put_contents('hashes.txt', $hash . PHP_EOL, FILE_APPEND);

        // fixme: eliminar header para manejarlo desde controlador
        header("Location: /confirmarEmail?hash=" . $hash);

        exit();
    }
}