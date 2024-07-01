<?php

class LogoutController{
    private $presenter;
    public function __construct($presenter){
        $this->presenter = $presenter;
    }

    public function get(){
        session_start();
        $_SESSION['loggedin'] = false;
        $_SESSION['idUsuario'] = null;
        header("Location: /login");
        exit();
    }
}