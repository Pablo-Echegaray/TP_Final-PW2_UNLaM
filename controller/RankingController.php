<?php
include_once("helper/phpqrcode/qrlib.php");

class RankingController {
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function ranking() {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
     $qrData = $this->model->getTotalRanking();
        $this->presenter->render("view/rankingView.mustache", ["qrData" => $qrData, "usuario" => $_SESSION["usuario"]]);
    }
}
?>
