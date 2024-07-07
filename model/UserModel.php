<?php
class UserModel
{
    private $database;
    private $maps;
    private $modelAdmin;
    private $modelQuestion;



    public function __construct($database, $maps, $modelAdmin, $modelQuestion)
    {
        $this->database = $database;
        $this->maps = $maps;
        $this->modelAdmin = $modelAdmin;
        $this->modelQuestion = $modelQuestion;
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

    public function getHomeData($rol, $usuario){
        $data = [];
        switch ($rol) {
            case 'J':
                if ($usuario[0]["activo"] == 0) {
                    $error = "Debes verificar tu correo para iniciar sesion";
                    $data[] = ["iniciarSesion", ["error" => $error]];
                } else {
                    $partidas = $this->getPartidasPorUsuario($usuario[0]['id']);
                    $ranking = $this->getRankingDelUsuario($usuario[0]['id']);
                    array_push($data, "home", ["usuario" => $usuario, "partidas" => $partidas, "ranking" => $ranking]);
                }
                break;
            case 'E':
                $estado = "activa";
                $preguntas = $this->modelQuestion->getQuestionsAndAnswers($estado);
                array_push($data, "editorHome", ["usuario" => $usuario, "preguntas" => $preguntas, "activas" => true]);
                break;
            case 'A':
                $jugadoresActivos = $this->modelAdmin->getActivePlayers();
                $jugadoresNuevos = $this->modelAdmin->getNewPlayers();
                $totalPartidas = $this->modelAdmin->getTotalGames();
                $totalPreguntas = $this->modelAdmin->getTotalQuestions();
                $totalPreguntasCreadas = $this->modelAdmin->getTotalCreatedQuestions();
                array_push($data, "adminHome", ["usuario" => $usuario, 'jugadoresActivos' => $jugadoresActivos, 'jugadoresNuevos' => $jugadoresNuevos, 'totalPartidas' => $totalPartidas, 'totalPreguntas' => $totalPreguntas, 'totalPreguntasCreadas' => $totalPreguntasCreadas]);
                break;
        }
        return $data;
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