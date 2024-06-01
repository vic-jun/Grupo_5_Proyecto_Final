<?php

class InicioController{

   // private $model;
    private $presenter;

    public function __construct( $presenter){
       // $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("views/inicio.mustache");
    }



}
