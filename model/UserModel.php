<?php
class UserModel
{
    private $database;
    private $maps;


    public function __construct($database, $maps)
    {
        $this->database = $database;
        $this->maps = $maps;
    }

    public function obtener($user, $pass)
    {
        return $this->database->query("
            SELECT * 
            FROM usuarios
            WHERE nombre_usuario = '$user' AND password = '$pass'
        ");
    }

    public function getUserById($id)
    {
        return $this->database->query("
            SELECT * 
            FROM usuarios
            WHERE id = '$id'
        ");
    }

    public function getPartidasPorUsuario($id)
    {
        $partidas = $this->database->query("
            SELECT jp.puntaje, jp.id_partida
            FROM jugadores_partidas jp
            WHERE jp.id_jugador = $id
        ");

        $contador = 1;

        $partidasConIds = [];
        foreach ($partidas as $partida) {
            $partida['id_secuencial'] = $contador;
            $partidasConIds[] = $partida;
            $contador++;
        }
    
        return $partidasConIds;

    }

    public function getRankingDelUsuario($idUsuario) {
        return $this->database->query(
            "SELECT u.id, u.nombre_usuario, jp.puntaje 
            FROM jugadores_partidas jp
            JOIN usuarios u ON jp.id_jugador = u.id
            WHERE jp.id_jugador = $idUsuario
              AND (jp.id_jugador, jp.puntaje) IN (
                SELECT jp2.id_jugador, MAX(jp2.puntaje) AS max_puntaje
                FROM jugadores_partidas jp2
                GROUP BY jp2.id_jugador
            )
            ORDER BY jp.puntaje DESC");
    }
    public function validarCodigo($username, $codigo)
    {
        $codigoUsuario = $this->obtenerCodigoDeUsuario($username);
        if ($codigo == $codigoUsuario[0]["codigo_verificacion"]) {
            $this->database->execute("
                UPDATE usuarios
                SET activo = 1
                WHERE nombre_usuario = '$username';
            ");
            return true;
        }
        return false;
    }

    public function getMarkByUser($userId){
        return $this->maps->getMarkByUser($userId);
    }

    private function obtenerCodigoDeUsuario($username)
    {
        return $this->database->query("
            SELECT usuarios.codigo_verificacion
            FROM usuarios
            WHERE nombre_usuario = '$username' 
        ");
    }
}