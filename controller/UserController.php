<?php
class UserController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function register()
    {
        $this->presenter->render("view/registroView.mustache");
    }

    public function codeGenerate()
    {
        $user = $_POST["username"] ?? "Completa este campo";
        $pass = $_POST["password"] ?? "Completa este campo";
        $usuario = $this->model->registrarUsuario($user, $pass);
        $this->presenter->render("view/codigoGenerado.mustache", ["usuario" => $usuario]);
    }

    public function login()
    {
        $this->presenter->render("view/inicioSesion.mustache");
    }

    public function play()
    {
        if (isset($_POST["user"]) && isset($_POST["code"]) && isset($_POST["pass"])) {
            $user = $_POST["user"];
            $codigo = $_POST["code"];
            $pass = $_POST["pass"];

            $usuario = $this->model->getUsuario($user,$codigo, $pass);
            var_dump($usuario);
        } else {
            echo "no hay datos correctos";
        }
    }
}