<?php

class RankingController{

    private $presenter;
    private $model;

    public function __construct($presenter, $model){
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get() {
        $ranking = $this->model->getRanking();
        $rankingWithIndex = array_map(function($key, $value) {
            return ['index' => $key + 1, 'value' => $value];
        }, array_keys($ranking), $ranking);
        //$this->presenter->render("views/ranking.mustache", ['ranking' => $ranking]);
        $this->presenter->render("views/ranking.mustache", ['ranking' => $rankingWithIndex]);
    }

}