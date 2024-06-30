<?php

class ErrorController{

    private $presenter;
    public function __construct(\MustachePresenter $getPresenter){
        $this->presenter = $getPresenter;
    }

    public function get(){
        $this->presenter->render("views/error.mustache");
    }
}