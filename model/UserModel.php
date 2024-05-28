<?php
class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function add($nombre, $contrasena)
    {
        $codigo = self::generarCodigo();
        $this->database->execute("
        INSERT INTO usuarios(nombre, contrasena, codigo)
        VALUES ('$nombre','$contrasena','$codigo')
        ");
    }

    private static function generarCodigo() { return "ABC".rand("100", "999"); }

    public function get($user, $pass)
    {
        return $this->database->query("
        SELECT * 
        FROM usuarios
        WHERE nombre = '$user' AND contrasena = '$pass'
        ");
    }
    /*public function getCodigoUsuario($codigo){
        return $this->database->query("SELECT * FROM usuario WHERE codigo = '$codigo'");
    }

    public function getUSuario($user, $codigo, $pass)
    {
      return $this->database->query("
            SELECT *
            FROM usuario
            WHERE name = '$user' AND pass = '$pass' AND codigo = '$codigo'");
    }*/

}