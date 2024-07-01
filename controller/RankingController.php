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

        $ranking = $this->model->getRanking();
        $qrData = [];

        foreach ($ranking as $user) {
            $filepath = 'public/qr_codes/qr_code_' . $user["id"] . '.png';

            if (!file_exists($filepath)) {
                $data = "/TP_Final-PW2_UNLaM/user/profile/id=" . $user["id"];
                $this->generateQRCode($data, $filepath);
            }

            $qrData[] = [
                "user" => $user,
                "qrPath" => $filepath
            ];
        }
        $this->presenter->render("view/rankingView.mustache", ["qrData" => $qrData, "usuario" => $_SESSION["usuario"]]);
    }

    private function generateQRCode($data, $filepath) {
        QRcode::png($data, $filepath, QR_ECLEVEL_L, 4);
    }
}
?>
