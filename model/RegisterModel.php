<?php
class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function calcularEdad($nacimiento)
    {
        $anio_actual = date("Y");
        return $anio_actual - $nacimiento;
    }

    public function verificarContrasena($contrasena, $contrasenaRepetida)
    {
        if ($contrasena == $contrasenaRepetida){
            return true;
        }
        return false;
    }

    public function verificarUsername($username)
    {
        $existe = $this->database->query("
            SELECT *
            FROM usuarios
            WHERE usuario = '$username' ");
        if (!$existe){
            return true;
        }
        return false;
    }

    public function verificarImagen($imagen)
    {
        if (isset($imagen)) {
                $targetDir = 'public/image/';
                $targetFile = $targetDir . basename($imagen['name']);
                if (move_uploaded_file($imagen['tmp_name'], $targetFile)) {
                    return $imagen["name"];
                }
        }
        return "perfil_sin_foto.jpg";
    }

    public function generarCodigo() { return "ABC".rand("100", "999"); }

    public function agregar($nombre, $apellido, $edad, $sexo, $pais, $ciudad, $mail, $contrasena, $usuario, $foto, $codigo)
    {
        $this->database->execute(
            "INSERT INTO usuarios(nombre, apellido, edad, sexo, pais, ciudad, mail, contrasena, usuario, foto, codigo)
             VALUES ('$nombre','$apellido','$edad','$sexo','$pais','$ciudad','$mail','$contrasena','$usuario','$foto','$codigo')"
        );
    }
}