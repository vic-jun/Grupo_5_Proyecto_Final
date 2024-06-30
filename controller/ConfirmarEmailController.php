<?php

class ConfirmarEmailController{

    private $model;
    private $presenter;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        if (isset($_GET['hash']) && isset($_GET['email'])) {
            $hash = $_GET['hash'];
            $email = $_GET['email'];

            $resultado = $this->model->confirmarEmail($email, $hash);
            $this->presenter->render("views/confirmarEmail.mustache", ['resultado' => $resultado]);
        } else {
            $data = array(
                "mensaje" => "No se ha podido confirmar el email"
            );
                $this->presenter->render("views/confirmarEmail.mustache" , $data);
        }


    }
}