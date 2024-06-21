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

    private function obtenerCodigoDeUsuario($username)
    {
        return $this->database->query("
            SELECT usuarios.codigo_verificacion
            FROM usuarios
            WHERE nombre_usuario = '$username' 
        ");
    }
}