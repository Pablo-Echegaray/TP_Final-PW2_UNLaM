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
            $this->renderHomeView($usuario);
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
                $this->renderHomeView($usuario);
            } else {
                $error = "Usuario o contraseña incorrecto";
                $this->presenter->render("view/iniciarSesionView.mustache", ["error" => $error]);
            }
        } else {
            if (isset($_SESSION["usuario"])) {
                $usuario = $_SESSION["usuario"];
                $this->renderHomeView($usuario);
            } else {
                $this->presenter->render("view/iniciarSesionView.mustache");
            }
        }
    }

    // Vista para cada uno de los usuarios
    private function renderHomeView($usuario)
    {
        $rol = $usuario[0]["rol"];

        switch ($rol) {
            case 'J':
                $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
                break;
            case 'E':
                $estado = "activa";
                $preguntas = $this->model->getQuestionsAndAnswers($estado);
                $this->presenter->render("view/editorHomeView.mustache", ["usuario" => $usuario, "preguntas" => $preguntas, "activas" => true]);
                break;
            case 'A':
                $this->presenter->render("view/adminView.mustache", ["usuario" => $usuario]);
                break;
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

    // Funciones editor
    public function approveQuestion()
    {
        $idPregunta = $_POST['preguntaId'];
        $this->model->approveQuestion($idPregunta);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }

    public function disapproveQuestion()
    {
        $idPregunta = $_POST['preguntaId'];
        $this->model->disapproveQuestion($idPregunta);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }
    
    public function deleteQuestion()
    {
        $idPregunta = $_POST['preguntaId'];
        $this->model->deleteAnswersForQuestion($idPregunta);
        $this->model->deleteQuestion($idPregunta);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }

    public function editQuestion()
    {
        $idPregunta = isset($_POST['preguntaId']) ? $_POST['preguntaId'] : null;
        $pregunta = $this->model->getQuestion($idPregunta);
        $respuestas = $this->model->getAnswers($idPregunta);
        $categorias =  $this->model->getCategorias();
        
        $this->presenter->render("view/editarPreguntaView.mustache", ["pregunta" => $pregunta, "usuario" => $_SESSION['usuario'], "respuestas" => $respuestas, "categorias" => $categorias]);
    }

    public function inactiveQuestions()
    {
        $estado = "inactiva";
        $preguntas = $this->model->getQuestionsAndAnswers($estado);
        $this->presenter->render("view/editorHomeView.mustache", ["usuario" =>  $_SESSION["usuario"], "preguntas" => $preguntas, "reportadas" => true]);
    }

    public function suggestedQuestions()
    {
        $estado = "sugerida";
        $preguntas = $this->model->getQuestionsAndAnswers($estado);
        $this->presenter->render("view/editorHomeView.mustache", ["usuario" =>  $_SESSION["usuario"], "preguntas" => $preguntas, "sugeridas" => true]);
    }

    public function reportedQuestions()
    {
        $estado = "reportada";
        $preguntas = $this->model->getQuestionsAndAnswers($estado);
        $this->presenter->render("view/editorHomeView.mustache", ["usuario" =>  $_SESSION["usuario"], "preguntas" => $preguntas, "reportadas" => true]);
    }




}