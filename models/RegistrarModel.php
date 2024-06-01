<?php



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
                $directorio_destino = $_SERVER['DOCUMENT_ROOT'] . '/public/img';

                if (!is_dir($directorio_destino)) {
                    mkdir($directorio_destino, 0755, true);
                }

                $nombre_foto = $directorio_destino . '/' . $fotoPerfil['name'];

                move_uploaded_file($archivo_temporal, $nombre_foto);
            } else {
                print_r($errors);
            }
        }

        $sql = "INSERT INTO usuario (nombre_de_usuario,nombre,apellido,email,password,pais,ciudad,foto,año_nacimiento,genero,rol) VALUES ('$nombreUsuario','$nombre','$apellido','$email','$password','$pais','$ciudad','$nombre_foto','$añoNacimiento','$genero','$rol')";

        include_once ("helper/Logger.php");
        $log = new Logger();
        $log->info($sql);

        $this->baseDeDatos->query($sql);


        $hash = hash('sha256', $email . $password);

        file_put_contents('hashes.txt', $hash . PHP_EOL, FILE_APPEND);

        return array("hash" => $hash, "email" => $email);
    }
}