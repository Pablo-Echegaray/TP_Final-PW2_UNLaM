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
        return $this->database->query(
            "SELECT * 
        FROM usuarios
        WHERE nombre_usuario = '$user' AND password = '$pass'"
        );
    }

    public function getUserById($id)
    {
        return $this->database->query(
        "SELECT * 
        FROM usuarios
        WHERE id = '$id'"
        );
    }
}