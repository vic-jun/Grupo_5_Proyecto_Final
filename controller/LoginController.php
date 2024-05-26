<?php

class LoginController
{
    private $presenter;

    public function __construct($presenter){
        $this->presenter = $presenter;
    }

    public function get(){
        $this->presenter->render("views/login.mustache");
    }
}