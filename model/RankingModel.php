<?php
class RankingModel {
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getRanking($limit = 15) {
        return $this->database->query(
            "SELECT u.nombre_usuario, jp.puntaje 
            FROM jugadores_partidas jp
            JOIN usuarios u ON jp.id_jugador = u.id
            WHERE (jp.id_jugador, jp.puntaje) IN (
                SELECT jp2.id_jugador, MAX(jp2.puntaje) AS max_puntaje
                FROM jugadores_partidas jp2
                GROUP BY jp2.id_jugador
            )
            ORDER BY jp.puntaje DESC");
    }

    /*public function getRanking($limit = 15) {
        $query = "...
                  LIMIT :limit";

        $params = array(":limit" => $limit);
        return $this->database->query($query, $params)->fetchAll();
    }*/
}