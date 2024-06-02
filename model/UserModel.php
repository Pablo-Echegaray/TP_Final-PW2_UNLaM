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
        SELECT * 
        FROM usuarios
        WHERE usuario = '$user' AND contrasena = '$pass'
        ");
    }
}