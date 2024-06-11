<?php

class LogoutController
{
    private $presenter;
    public function __construct($presenter)
    {
        $this->presenter = $presenter;
    }

    public function get()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }
}