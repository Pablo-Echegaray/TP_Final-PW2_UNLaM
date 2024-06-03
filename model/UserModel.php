<?php
class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtener($user, $pass)
    {
        return $this->database->query("
            SELECT *, s.descripcion AS sexo_descripcion
            FROM usuarios u
            INNER JOIN sexos s ON u.id_sexo = s.id
            WHERE u.nombre_usuario = '$user' AND u.password = '$pass'
        ");
    }

    public function getPregunta($numRandom)
    {

        return $this->database->query(
        "SELECT descripcion
        FROM preguntas
        WHERE id = 1" //(probando)
        );
    }

    public function getRespuestas($numRandom)
    {
        return $this->database->query(

            "SELECT p.id, p.descripcion, ri.descripcion 
            FROM preguntados.preguntas p 
            INNER JOIN preguntas_respuestas_incorrectas pri ON pri.id_pregunta = p.id 
            INNER JOIN respuestas_incorrectas ri ON ri.id = pri.id_respuesta_incorrecta 
            WHERE p.id = 1 
            UNION SELECT p.id, p.descripcion, rc.descripcion
            FROM preguntados.preguntas p 
            INNER JOIN respuestas_correctas rc ON p.id_respuesta_correcta = rc.id 
            WHERE p.id = 1;
            "
        );
    }

    public function getRespuestaCorrecta($numRandom)
    {
        return $this->database->query(

            "SELECT rc.descripcion
            FROM preguntados.preguntas p 
            INNER JOIN preguntados.respuestas_correctas rc ON p.id_respuesta_correcta = rc.id
            WHERE p.id = 1"
        );
    }

}