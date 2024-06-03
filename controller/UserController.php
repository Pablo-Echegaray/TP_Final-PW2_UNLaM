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
                echo "No registrado";
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
            var_dump($usuario);
            $this->presenter->render("view/perfilView.mustache", ["usuario" => $usuario]);
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

    public function play()
    {
        $idRandom = rand(1, 25);
        $pregunta = $this->model->getPregunta($idRandom);
        $respuestas = $this->model->getRespuestas($idRandom);

        $respuestaCorrecta = $this->model->getRespuestaCorrecta($idRandom);
        $correcta = "París"; // (prueba)

        $this->presenter->render("view/jugarView.mustache", ["usuario" => $_SESSION["usuario"], "preguntas" => $pregunta, "respuestas" => $respuestas, "respuestas_correctas" => $respuestaCorrecta]);

        //(probando)
        if (isset($_POST['respuesta'])) {
            $respuestaDelUsuario = $_POST['respuesta'];
            if ($respuestaDelUsuario == $correcta) {
                echo "respuesta correcta";
            } else {
                echo "incorrecta";
                echo " correcta: " . $correcta;
                echo " usuario: " . $respuestaDelUsuario;
            }
        } else {
            echo "sin respuesta";
        }

    }

}