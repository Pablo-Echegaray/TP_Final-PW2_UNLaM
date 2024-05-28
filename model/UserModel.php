<?php
class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function get($user, $pass)
    {
        return $this->database->query("
        SELECT * 
        FROM usuarios
        WHERE nombre = '$user' AND contrasena = '$pass'
        ");
    }
}