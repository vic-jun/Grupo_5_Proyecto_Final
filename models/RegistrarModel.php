<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ajusta las rutas según la estructura de tu proyecto
require_once __DIR__ . '/../vendor/PHPMailer-6.9.1/src/Exception.php';
require_once __DIR__ . '/../vendor/PHPMailer-6.9.1/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer-6.9.1/src/SMTP.php';

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
                $errors[] = 'El tamaño del archivo debe ser menor de 2 MB';
            }

            if (empty($errors)) {
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
                return false;
            }
        }

        $hash = hash('sha256', $email . $password);

        $sql = "INSERT INTO usuario (nombre_de_usuario, nombre, apellido, email, password, pais, ciudad, foto, año_nacimiento, genero, rol, hash, confirmed) 
                VALUES ('$nombreUsuario', '$nombre', '$apellido', '$email', '$password', '$pais', '$ciudad', '$nombre_foto', '$añoNacimiento', '$genero', '$rol', '$hash', 0)";

        $this->baseDeDatos->query($sql);

        file_put_contents('hashes.txt', $hash . PHP_EOL, FILE_APPEND);

        return array("hash" => $hash, "email" => $email);
    }

    public function sendEmail($email, $username, $hash) {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $this->getEmailConfig()['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->getEmailConfig()['username'];
            $mail->Password = $this->getEmailConfig()['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->getEmailConfig()['port'];
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($this->getEmailConfig()['address'], 'PREGUNTADITOS');
            $mail->addAddress($email, $username);
            $mail->Subject = 'Validación de cuenta';
            $mail->isHTML(true);
            $mail->Body = '<h1> Tu URL para activar tu correo </h1>
                            Haz click <a href="http://' . $_SERVER['SERVER_NAME'] . '/registrar/validateEmail?hash=' . $hash . '">en este link</a> para validar tu email';
            $mail->AltBody = 'Si no puedes ver este mensaje, por favor, habilita el soporte para HTML en tu cliente de correo.';
            $mail->send();
        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo . ' ' . $e;
        }
    }

    private function getEmailConfig() {
        $config = json_decode(file_get_contents(__DIR__ . '/../config/email_config.json'), true);
        return $config;
    }

    public function validateEmail($hash) {
        $sql = "UPDATE usuario SET confirmed = 1 WHERE hash = '$hash'";
        return $this->baseDeDatos->query($sql);
    }
    public function emailConfirmado($correoUsuario){
        $sql = "SELECT 1 FROM usuario WHERE email = '$correoUsuario' AND confirmed = '1'";
        $queryResult = $this->baseDeDatos->query($sql);
        if($queryResult === false){
            return false;
        }
        return $queryResult->num_rows > 0;
    }

}
