<?php
class RegisterModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validar($valor)
    {
        if (isset($valor) && !empty(trim($valor))){
            return $valor;
        } else{
            die("Campo no valido");
        }
    }

    public function calcularEdad($nacimiento)
    {
        self::validar($nacimiento);
        $anio_actual = date("Y");
        if ($nacimiento > $anio_actual || $nacimiento == $anio_actual) {
            die("Ingrese una correcta edad");
        }
        return $anio_actual - $nacimiento;
    }

    public function verificarContrasena($contrasena, $contrasenaRepetida)
    {
        $this->validar($contrasena);
        if ($contrasena == $contrasenaRepetida){
            return $contrasena;
        } else{
            die("Contrseñas distintas");
        }
    }

    public function verificarUsuario($username)
    {
        self::validar($username);
        $existe = $this->database->query("
            SELECT *
            FROM usuarios
            WHERE usuario = '$username'
        ");
        if ($existe){
            die("Usuario existente, ingrese otro");
        } else {
            return $username;
        }
    }

    public function verificarImagen($imagen)
    {
        if (isset($imagen)) {
            if ($imagen["size"] > 0){
                $upload = "/TP_Final-PW2_UNLaM/public/image/";
                if (move_uploaded_file($imagen["tmp_name"], $upload)){
                    return $imagen["name"];
                }
            } else { return "perfil_sin_foto.jpg"; }
        } else { return "perfil_sin_foto.jpg"; }
    }

    public function generarCodigo() {
        return "ABC".rand("100", "999");
    }

    public function add($nombre, $apellido, $edad, $sexo, $pais, $ciudad, $mail, $contrasena, $usuario, $foto, $codigo)
    {
        $this->database->execute(
            "INSERT INTO usuarios(nombre, apellido, edad, sexo, pais, ciudad, mail, contrasena, usuario, foto, codigo)
             VALUES ('$nombre','$apellido','$edad','$sexo','$pais','$ciudad','$mail','$contrasena','$usuario','$foto','$codigo')"
        );
    }
}