<?php

class ConfirmarEmailController
{

    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get()
    {
        $data = $this->model->confirmarEmail();
        $this->presenter->render("views/confirmarEmail.mustache" , $data);
    }
}