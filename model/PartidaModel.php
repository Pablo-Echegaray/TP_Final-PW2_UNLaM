<?php
class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function crearPartida($modo)
    {
        $this->database->execute("INSERT INTO partidas (modo, estado) VALUES ('$modo', 'playing')");
        return $this->database->query("SELECT * FROM partidas");
    }

    public function getLastGame(){
        return $this->database->query_for_one("SELECT id, modo, estado
                                        FROM partidas
                                        ORDER BY id DESC
                                        LIMIT 1;");
    }


    public function getPreguntaRandom($usuarioId)
    {
        $id = $this->getIdNextQuestion($usuarioId);

        return $this->database->query("
            SELECT preguntas.id, preguntas.descripcion
            FROM preguntas 
            WHERE preguntas.id = '$id' ;
        ");
    }

    private function obtenerPrimerNumero()
    {
        return $this->database->query("
            SELECT preguntas.id
            FROM preguntas
            ORDER BY preguntas.id ASC
            LIMIT 1;
        ");
    }

    private function obtenerSegundoNumero()
    {
        return $this->database->query("
            SELECT preguntas.id
            FROM preguntas
            ORDER BY preguntas.id DESC 
            LIMIT 1;
        ");
    }

    public function asignarPreguntaAPartida($idPartida, $id_Pregunta)
    {
        return $this->database->execute("
            INSERT INTO partidas_preguntas (id_Partida, id_Pregunta) VALUES('$idPartida', '$id_Pregunta');
        ");
    }

    public function getRespuestas($idPregunta)
    {
        return $this->database->query("
            SELECT p.id, p.descripcion, r.descripcion 
            FROM preguntados.preguntas p 
            INNER JOIN respuestas r ON r.id_pregunta = $idPregunta 
            WHERE p.id = $idPregunta;
        ");
    }

    public function getRespuestaCorrecta($idPregunta)
    {
        return $this->database->query_for_one(

            "SELECT descripcion
             FROM preguntados.respuestas
             WHERE id_pregunta = $idPregunta
             AND estado = 1;"
        );

    }

    public function getLastQuestionInGame(){
        return $this->database->query_for_one(
            "SELECT subquery.id_partida, subquery.id_pregunta, subquery.descripcion
             FROM (
                    SELECT pp.id_partida, pp.id_pregunta, pre.descripcion
                    FROM preguntados.partidas_preguntas pp
                    INNER JOIN preguntados.preguntas pre ON pp.id_pregunta = pre.id
                    ORDER BY pp.fecha_creacion DESC
                    LIMIT 1
                  ) AS subquery;");
    }

    public function endGame($idPartida){
        $this->database->execute("UPDATE partidas
                                   SET estado = 'finished'
                                   WHERE id = $idPartida;");
    }

    public function actualizarPuntaje($idPartida){
        $this->database->execute("UPDATE jugadores_partidas
                                   SET puntaje = puntaje + 5
                                   WHERE id_partida = $idPartida;");
    }

    public function getPuntajeJugadorEnPartida($idPartida)
    {
        return $this->database->query_for_one("
            SELECT puntaje
            FROM jugadores_partidas
            WHERE id_partida = '$idPartida';
        ");
    }
        
    public function getQuestionsByPlayer($idJugador){
        return $this->database->query("
            SELECT pp.id_pregunta
            FROM preguntados.jugadores_partidas jp
            INNER JOIN partidas_preguntas pp ON jp.id_partida = pp.id_partida
            WHERE id_jugador = $idJugador;
        ");
    }

    public function asignarPartidaAJugador($idJugador, $idPartida, $puntaje)
    {
        return $this->database->execute("
           INSERT INTO jugadores_partidas (id_Jugador, id_Partida, puntaje) 
           VALUES ('$idJugador', '$idPartida', '$puntaje');
        ");
    }

    public function buscarPartidaAsignadaAJugador($idJugador, $idPartida)
    {
        return $this->database->query("
        SELECT jugadores_partidas.id_partida
        FROM jugadores_partidas
        WHERE jugadores_partidas.id_jugador = '$idJugador' AND jugadores_partidas.id_partida = '$idPartida';
        ");
    }

    public function obtenerCategoriaPregunta($idPregunta)
    {
        return $this->database->query("
            SELECT c.descripcion
            FROM preguntas p
            INNER JOIN categorias c ON p.id_categoria = c.id
            WHERE p.id = '$idPregunta';
        ");
    }

    public function updateQuestionDeliveredAndHit($idPregunta, $value){
        $this->database->execute("
               UPDATE preguntas
                SET
                    entregadas = entregadas + 1,
                    hit = hit + $value
                WHERE
                    id = $idPregunta;");

    }

    public function updateUserDeliveredAndHit($idUsuario, $value){
        $this->database->execute("
               UPDATE usuarios
                SET
                    entregadas = entregadas + 1,
                    hit = hit + $value
                WHERE
                    id = $idUsuario;");

    }

    private function dontRepeatTheQuestionToThePlayer($idJugador, $idNewQuestion): bool{
        $questionsId = $this->getQuestionsByPlayer($idJugador);
        foreach ($questionsId as $questionId) {
            if ($questionId["id_pregunta"] == $idNewQuestion) {
                //echo "id pregunta". $questionId["id_pregunta"];
                //echo "id new question". $idNewQuestion;
                return true;
            }
        }
        return false;
    }

    private function getIdNextQuestion($usuarioId): int {
        $apropriateQuestions = $this->selectQuestionsByDifficulty($usuarioId);
        foreach ($apropriateQuestions[0] as $question) {
            //echo $question["descripcion"];
        }
        do {
            //$idNextQuestion = rand($this->obtenerPrimerNumero()[0]["id"], $this->obtenerSegundoNumero()[0]["id"]);
            $idNextQuestion = $apropriateQuestions[0][rand(0, count($apropriateQuestions[0])-1)]["id"];
        } while ($this->dontRepeatTheQuestionToThePlayer($usuarioId, $idNextQuestion));
        return $idNextQuestion;
    }

    private function setDifficulty(){
        $questions = $this->database->query("SELECT * FROM preguntas WHERE estado = 'activa'");
        $easyQuestions = [];
        $mediumQuestions = [];
        $hardQuestions = [];
        //array_push($array, "valor1", "valor2");
        foreach ($questions as $question) {
            if ($question["hit"] /$question["entregadas"] > 0.6) {
                array_push($easyQuestions, $question);

            }else if ($question["hit"] /$question["entregadas"] < 0.6 && $question["hit"] /$question["entregadas"] > 0.4) {
                array_push($mediumQuestions, $question);
            }else{
                array_push($hardQuestions, $question);
            }
        }
        return array("easyQuestions" => $easyQuestions, "mediumQuestions" => $mediumQuestions, "hardQuestions" => $hardQuestions);
    }

    private function getPlayerExperience($playerId){
        $user = $this->database->query("SELECT * FROM usuarios WHERE id = $playerId;");
        return $user[0]["hit"] / $user[0]["entregadas"];
    }

    private function selectQuestionsByDifficulty($playerId){
        $questionsByDifficulty = $this->setDifficulty();
        $playerExperience = $this->getPlayerExperience($playerId);
        $apropriateQuestions = [];
        if ($playerExperience > 0.6) {
            array_push($apropriateQuestions, $questionsByDifficulty["hardQuestions"]);
            //$apropriateQuestions = $questionsByDifficulty["hardQuestions"];
        }else if ($playerExperience > 0.4 && $playerExperience < 0.6){
            array_push($apropriateQuestions, $questionsByDifficulty["mediumQuestions"]);
            //$apropriateQuestions = $questionsByDifficulty["mediumQuestions"];
        }else{
            array_push($apropriateQuestions, $questionsByDifficulty["easyQuestions"]);
            //$apropriateQuestions = $questionsByDifficulty["easyQuestions"];
        }
        return $apropriateQuestions;
    }
}