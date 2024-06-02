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
        $this->presenter->render("view/registrarseView.mustache");
    }

    public function add() {
        $nombre = $_POST["nombre"] ?? "";
        $apellido = $_POST["apellido"] ?? "";
        $nacimiento = $_POST["nacimiento"];
        $sexo = $_POST["sexo"] ?? "";
        $pais = $_POST["pais"] ?? "";
        $ciudad = $_POST["ciudad"] ?? "";
        $mail = $_POST["mail"] ?? "";
        $contrasena = self::validarContrasena($_POST["password"], $_POST["repeat_password"]);
        $usuario = self::validarUsuario($_POST["username"]);
        $foto = $this->model->verificarImagen($_FILES["perfil"]);
        $codigo = $this->model->generarCodigo();

        if ($contrasena != null) {
            if ($usuario != null) {
                $this->model->agregar($nombre, $apellido, $nacimiento, $sexo, $pais, $ciudad, $mail, $contrasena, $usuario, $foto, $codigo);
                header("Location: /TP_Final-PW2_UNLaM/user/get/".$codigo);
                exit();
            } else {
                $error = "El nombre de usuario ya existe";
                $this->presenter->render("view/registrarseView.mustache", ["error" => $error]);
            }
        } else {
            $error = "Las contraseñas no coinciden";
            $this->presenter->render("view/registrarseView.mustache", ["error" => $error]);
        }
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
