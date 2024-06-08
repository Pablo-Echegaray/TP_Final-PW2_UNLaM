<?php
class RankingController {
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function ranking() {
        $ranking = $this->model->getRanking();
        $this->presenter->render("view/rankingView.mustache", array("ranking" => $ranking));
    }
}