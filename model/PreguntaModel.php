<?php
class PreguntaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getCategorias(){
        return $this->database->query(
            "SELECT * 
             FROM categorias"
        );
    }
}