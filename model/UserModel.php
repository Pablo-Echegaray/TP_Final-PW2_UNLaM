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

}