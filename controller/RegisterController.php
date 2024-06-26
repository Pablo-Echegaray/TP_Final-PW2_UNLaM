<?php
class RegisterController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function get() {

        $this->presenter->render("view/registrarseView.mustache", ["listMaps" => $this->getMaps()]);
        //$this->presenter->render("view/registrarseView.mustache");
    }

    public function add() {
        $nombre = $_POST["nombre"] ?? "";
        $apellido = $_POST["apellido"] ?? "";
        $nacimiento = $_POST["nacimiento"];
        $sexo = $_POST["sexo"] ?? "";
        $pais = $_POST["pais"] ?? "";
        $ciudad = $_POST["ciudad"] ?? "";
        $email = $_POST["mail"] ?? "";
        $contrasena = self::validarContrasena($_POST["password"], $_POST["repeat_password"]);
        $usuario = self::validarUsuario($_POST["username"]);
        $foto = $this->model->verificarImagen($_FILES["perfil"]);
        $lat = $_POST["lat"] ?? "";
        $lng = $_POST["lng"] ?? "";
        $rol = "J";

        if ($contrasena != null) {
            if ($usuario != null) {
                //AGREGA AL USUARIO COMO NO ACTIVO y POR DEFECTO COMO JUGADOR
                $this->model->agregar($nombre, $apellido, $nacimiento, $sexo, $ciudad, $pais, $email, $contrasena, $usuario, $foto);
                $this->model->saveCoordinates($usuario, $ciudad, $pais, $lat, $lng);
                $this->model->enviarCorreoVerificacion($email, $nombre, $usuario);

                $mensajeVerificacion = "Verifica tu correo para iniciar sesion";
                $this->presenter->render("view/iniciarSesionView.mustache", ["mensaje" => $mensajeVerificacion]);
            } else {
                $error = "El nombre de usuario ya existe";
                $this->presenter->render("view/registrarseView.mustache", ["error" => $error]);
            }
        } else {
            $error = "Las contraseñas no coinciden";
            $this->presenter->render("view/registrarseView.mustache", ["error" => $error]);
        }
    }

    private function getMaps(){
        $listMaps = $this->model->getMaps();
        //echo $listMaps[0][0];
        return $listMaps;
    }

    private function validarNacimiento($nacimiento)
    {
        if (isset($nacimiento)) {
            return $this->model->calcularEdad($nacimiento);
        }
        return "";
    }

    private function validarContrasena($contrasena, $contrasenaRepetida)
    {
        if (isset($contrasena) && isset($contrasenaRepetida)){
            if ($this->model->verificarContrasena($contrasena, $contrasenaRepetida)){
                return $contrasena;
            } else{ return null; }
        }
        return null;
    }

    private function validarUsuario($username)
    {
        if (isset($username)) {
            if ($this->model->verificarUsername($username)) {
                return $username;
            } else { return null; }
        }
        return null;
    }
}