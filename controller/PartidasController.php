<?php

class PartidasController{
    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get() {
        $partida = $this->model->getPartidas();
        $partidaWithIndex = array_map(function($key, $value) {
            return ['index' => $key + 1, 'value' => $value];
        }, array_keys($partida), $partida);
        $this->presenter->render("views/partidas.mustache", ['partida' => $partidaWithIndex]);
    }

}