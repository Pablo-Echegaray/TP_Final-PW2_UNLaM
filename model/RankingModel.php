<?php
class RankingModel {
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getTotalRanking(){
        $ranking = $this->getRanking();
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
        return $qrData;
    }
    private function getRanking($limit = 15) {
        return $this->database->query(
            "SELECT DISTINCT u.id, u.nombre_usuario, jp.puntaje
            FROM jugadores_partidas jp
            JOIN usuarios u ON jp.id_jugador = u.id
            WHERE (jp.id_jugador, jp.puntaje) IN (
                SELECT jp2.id_jugador, MAX(jp2.puntaje) AS max_puntaje
                FROM jugadores_partidas jp2
                GROUP BY jp2.id_jugador
            )
            ORDER BY jp.puntaje DESC");
    }

    private function generateQRCode($data, $filepath) {
        QRcode::png($data, $filepath, QR_ECLEVEL_L, 4);
    }
}