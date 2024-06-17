<?php

class InicioController{

   // private $model;
    private $presenter;

    public function __construct( $presenter){
       // $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get(){
        session_start();
        if (isset($_GET['timeout']) && $_GET['timeout'] == 'true') {
            unset($_SESSION['preguntaID']);
        }

        $this->presenter->render("views/inicio.mustache");
    }

}
