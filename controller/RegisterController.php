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

    public function agregar() {
        $nombre = $this->model->validar($_POST["nombre"]);
        $apellido = $this->model->validar($_POST["apellido"]);
        $edad = $this->model->calcularEdad($_POST["nacimiento"]);
        $sexo = $this->model->validar($_POST["sexo"]);
        $pais = $this->model->validar($_POST["pais"]);
        $ciudad = $this->model->validar($_POST["ciudad"]);
        $mail = $this->model->validar($_POST["mail"]);
        $contrasena = $this->model->verificarContrasena($_POST["password"], $_POST["repeat_password"]);
        $usuario = $this->model->verificarUsuario($_POST["username"]);
        $foto = $this->model->verificarImagen($_FILES["perfil"]);
        $codigo = $this->model->generarCodigo();
        $this->model->add($nombre, $apellido, $edad, $sexo, $pais, $ciudad, $mail, $contrasena, $usuario, $foto, $codigo);
        //header("Location: Location: /TP_Final-PW2_UNLaM/");
        //exit();
    }
}
