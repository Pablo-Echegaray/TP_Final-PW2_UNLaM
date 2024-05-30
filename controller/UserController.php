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
        if (isset($_POST["user"]) && isset($_POST["pass"])) {
            $user = $_POST["user"];
            $pass = $_POST["pass"];
            $usuario = $this->model->obtener($user, $pass);
    
            if ($usuario) {
                $_SESSION["usuario"] = $usuario;
                $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
                return 0;
            } else {
                echo "No registrado";
            }
        } else {
            // verificamos si la sesión está activa antes de mostrar la vista
            if (isset($_SESSION["usuario"])) {
                $usuario = $_SESSION["usuario"];
                $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
            } else {
                // Si no hay una sesión activa, redirigir al usuario a la página de inicio de sesión
                $this->presenter->render("view/iniciarSesionView.mustache");
            }
        }
    }
    
    public function perfil()
    {
        $this->presenter->render("view/perfilView.mustache", ["usuario" => $_SESSION["usuario"]]);
    }

    
}