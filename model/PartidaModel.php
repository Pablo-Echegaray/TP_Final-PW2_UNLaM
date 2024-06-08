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
        $this->database->execute("INSERT INTO partidas (modo) VALUES ('$modo')");
        return $this->database->query("SELECT * FROM partidas");
    }

    public function getPreguntaRandom()
    {
        $inicio = self::obtenerPrimerNumero();
        $final = self::obtenerSegundoNumero();
        $id = rand($inicio[0]["id"], $final[0]["id"]);
        return $this->database->query("
            SELECT preguntas.id, preguntas.descripcion
            FROM preguntas 
            WHERE preguntas.id = '$id';
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

    public function getRespuestaCorrecta()
    {
        return $this->database->query_for_one(

            "SELECT descripcion
            FROM respuestas
            WHERE id_pregunta = 1
            AND estado = 1;"
        );
        
    }

}