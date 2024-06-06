<?php
class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function add($modo)
    {
        return $this->database->execute(
            "INSERT INTO partidas (modo) VALUES ('$modo')"
        );
    }

    public function getPreguntaRandom()
    {
        return $this->database->query(

            "SELECT preguntas.id, preguntas.descripcion
            FROM preguntas 
            WHERE preguntas.id = 1;"

        );
    }

    public function asignarPreguntaAPartida($preguntaId, $idPartida)
    {

    }

    public function getRespuestas()
    {
        return $this->database->query(

            "SELECT p.id, p.descripcion, r.descripcion 
            FROM preguntados.preguntas p 
            INNER JOIN respuestas r ON r.id_pregunta = p.id 
            WHERE p.id = 1;"
        );
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