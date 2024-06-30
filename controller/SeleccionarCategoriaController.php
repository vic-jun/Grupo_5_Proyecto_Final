<?php

class SeleccionarCategoriaController{
    private $presenter;
    private $model;

    public function __construct($model, $presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("views/seleccionarCategoria.mustache");
    }

    public function seleccionar(){
        $data = $this->model->seleccionar($_POST["categoria"]);
    }

}
