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

    public function get()
    {
        if (isset($_SESSION["usuario"])) {
            $usuario = $_SESSION["usuario"];
            $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
        } else {
            $this->presenter->render("view/iniciarSesionView.mustache");
        }
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
                $error = "Usuario o contraseña incorrecto";
                $this->presenter->render("view/iniciarSesionView.mustache", ["error" => $error]);
            }
        } else {
            if (isset($_SESSION["usuario"])) {
                $usuario = $_SESSION["usuario"];
                $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
            } else {
                $this->presenter->render("view/iniciarSesionView.mustache");
            }
        }
    }

    public function porfile()
    {
        if (isset($_SESSION["usuario"])) {
            $usuario = $_SESSION["usuario"];
            $this->presenter->render("view/perfilView.mustache", ["usuario" => $_SESSION["usuario"]]);
        } else {
            $this->presenter->render("view/iniciarSesionView.mustache");
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        $this->presenter->render("view/iniciarSesionView.mustache");
    }
//$_SERVER['REQUEST_URI']
    public function profile() 
    {
        $id = $_GET['id'];
        $usuario = $this->model->getUserById($id);
        if (isset($id) && $usuario) {
            $this->presenter->render("view/otherUsersView.mustache", array("usuario" => $usuario));
        } else {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/ranking/ranking');
            exit();
        }
    }
    public function addQuestion() 
    {
        if (isset($_SESSION["usuario"])) {
            $categorias = $this->model->getCategorias();
            $this->presenter->render("view/crearPregunta.mustache",[ "usuario" => $_SESSION["usuario"], "categorias" => $categorias]);
        }
    }
}