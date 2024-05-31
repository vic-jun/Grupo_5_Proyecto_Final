<?php

class RegistrarController
{
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
        $data = $this->model->registrarse($_POST["usuario"],$_POST["nombre"],$_POST["apellido"],$_POST["correo"],$_POST["contrasenia"],$_POST["pais"],$_POST["ciudad"],$_FILES["foto"],$_POST["anioDeNacimiento"],$_POST["genero"]);
        $this->presenter->render("views/registrar.mustache", $data);
    }
}