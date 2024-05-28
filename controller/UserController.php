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

    public function get() {
        $this->presenter->render("view/iniciarSesionView.mustache");
    }

    public function home()
    {
        $user = $_POST["user"] ?? "";
        $pass = $_POST["pass"] ?? "";
        $usuario = $this->model->get($user, $pass);
        if ($usuario != null) {
            $_SESSION["usuario"] = $usuario;
            $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
        } else {
            header("Location: /TP_Final-PW2_UNLaM/");
        }
    }
}