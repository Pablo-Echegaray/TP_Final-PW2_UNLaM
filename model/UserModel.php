<?php
class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarUsuario($user, $pass)
    {
        $codigo = self::generarCodigo();
        $this->database->execute("INSERT INTO usuario(name, pass, codigo) VALUES ('$user','$pass','$codigo')");
        return $this->getCodigoUsuario($codigo);
    }

    public function getCodigoUsuario($codigo){
        return $this->database->query("SELECT * FROM usuario WHERE codigo = '$codigo'");
    }

    private static function generarCodigo() {
        return "ABC".rand("100", "999");
    }
}