<?php

class RegistrarController{
    private $presenter;
    private $model;

    public function __construct($model, $presenter){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get(){
        $this->presenter->render("views/registrar.mustache");
    }

    public function registrar(){
        $result = $this->model->registrarse($_POST["usuario"], $_POST["nombre"], $_POST["apellido"], $_POST["correo"], $_POST["contrasenia"], $_POST["pais"], $_POST["ciudad"], $_FILES["foto"], $_POST["anioDeNacimiento"], $_POST["genero"]);

        if (!array_key_exists("error1", $result) && !array_key_exists("error2", $result)) {
            $this->model->sendEmail($_POST["correo"], $_POST["usuario"], $result["hash"]);

            if ($this->model->emailConfirmado($_POST["correo"])) {
                $data = array("message" => "El correo ya ha sido confirmado.");
            } else {
                $data = array("message" => "Verifica tu casilla de correo para confirmar tu dirección de correo electrónico.");
            }
            $this->presenter->render("views/confirmarEmail.mustache", $data);
            exit();
        } else {
            $result['error'] = "No se ha podido registrar el usuario";
            $result['showError'] = !empty($result['error']);
            $result['showError1'] = array_key_exists("error1", $result) && !empty($result['error1']);
            $result['showError2'] = array_key_exists("error2", $result) && !empty($result['error2']);
            $this->presenter->render("views/registrar.mustache", $result);
        }
    }

    public function validateEmail(){
        if (isset($_GET['hash'])) {
            $hash = $_GET['hash'];
            $this->model->validateEmail($hash);
            $data = array("message" => "Su correo ha sido validado exitosamente.");
            $this->presenter->render("views/confirmarEmail.mustache", $data);
        } else {
            $data = array("error" => "No se ha podido validar el correo.");
            $this->presenter->render("views/registrar.mustache", $data);
        }
    }
}
