<?php
class UserController
{
    private $presenter;
    private $modelUser;
    private $modelQuestion;
    private $modelAdmin;

    public function __construct($modelUser, $modelAdmin, $modelQuestion, $presenter)
    {
        $this->presenter = $presenter;
        $this->modelUser = $modelUser;
        $this->modelQuestion = $modelQuestion;
        $this->modelAdmin = $modelAdmin;
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
            $usuario = $this->modelUser->obtener($user, $pass);

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
        echo $rol;

        switch ($rol) {
            case 'J':
                if ($usuario[0]["activo"] == 0){
                    $error = "Debes verificar tu correo para iniciar sesion";
                    $this->presenter->render("view/iniciarSesionView.mustache", ["error" => $error]);
                } else {
                    $this->presenter->render("view/homeView.mustache", ["usuario" => $usuario]);
                }
                break;
            case 'E':
                $estado = "activa";
                $preguntas = $this->modelQuestion->getQuestionsAndAnswers($estado);
                $this->presenter->render("view/editorHomeView.mustache", ["usuario" => $usuario, "preguntas" => $preguntas, "activas" => true]);
                break;
            case 'A':
                $jugadoresActivos = $this->modelAdmin->getActivePlayers();
                $totalPartidas = $this->modelAdmin->getTotalGames();
                $totalPreguntas = $this->modelAdmin->getTotalQuestions();
                $totalPreguntasCreadas = $this->modelAdmin->getTotalCreatedQuestions();
                $this->presenter->render("view/adminHomeView.mustache", ["usuario" => $usuario, 'jugadoresActivos' => $jugadoresActivos, 'totalPartidas' => $totalPartidas, 'totalPreguntas' => $totalPreguntas, 'totalPreguntasCreadas' => $totalPreguntasCreadas]);
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
        $usuario = $this->modelUser->getUserById($id);
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
        $this->modelQuestion->approveQuestion($idPregunta);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }

    public function disapproveQuestion()
    {
        $idPregunta = $_POST['preguntaId'];
        $this->modelQuestion->disapproveQuestion($idPregunta);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }

    public function deleteQuestion()
    {
        $idPregunta = $_POST['preguntaId'];
        $this->modelQuestion->deleteAnswersForQuestion($idPregunta);
        $this->modelQuestion->deleteQuestion($idPregunta);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 
    }

    public function editQuestion()
    {
        $idPregunta = isset($_POST['preguntaId']) ? $_POST['preguntaId'] : null;
        $pregunta = $this->modelQuestion->getQuestion($idPregunta);
        $respuestas = $this->modelQuestion->getAnswers($idPregunta);
        $categorias =  $this->modelQuestion->getCategorias();


        $this->presenter->render("view/editarPreguntaView.mustache", ["pregunta" => $pregunta, "usuario" => $_SESSION['usuario'], "respuestas" => $respuestas, "categorias" => $categorias]);
    }

    public function inactiveQuestions()
    {
        $estado = "inactiva";
        $preguntas = $this->modelQuestion->getQuestionsAndAnswers($estado);
        $this->presenter->render("view/editorHomeView.mustache", ["usuario" =>  $_SESSION["usuario"], "preguntas" => $preguntas, "reportadas" => true]);
    }

    public function suggestedQuestions()
    {
        $estado = "sugerida";
        $preguntas = $this->modelQuestion->getQuestionsAndAnswers($estado);
        $this->presenter->render("view/editorHomeView.mustache", ["usuario" =>  $_SESSION["usuario"], "preguntas" => $preguntas, "sugeridas" => true]);
    }

    public function reportedQuestions()
    {
        $estado = "reportada";
        $preguntas = $this->modelQuestion->getQuestionsAndAnswers($estado);
        $this->presenter->render("view/editorHomeView.mustache", ["usuario" =>  $_SESSION["usuario"], "preguntas" => $preguntas, "reportadas" => true]);
    }


    public function updateQuestion(){
        $idPregunta = isset($_POST['id_pregunta']) ? $_POST['id_pregunta'] : null;
        $pregunta = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
        $idCategoria =  isset($_POST['categoria']) ? $_POST['categoria'] : null;
        $respuestaIds = isset($_POST['respuestaId']) ? $_POST['respuestaId'] : null;
        $respuestaDescripciones = isset($_POST['opcion']) ? $_POST['opcion'] : null;
        $correcta = isset($_POST['correcta']) ? $_POST['correcta'] : null;
        $answers = [];

        for ($i=0; $i < count($respuestaIds); $i++) {
            $answers[$i] = ["id" => "$respuestaIds[$i]", "descripcion" => $respuestaDescripciones[$i], "estado" => 0];

        }

        $this->modelQuestion->editQuestionAndAnswers($idPregunta, $idCategoria, $pregunta, $answers, $correcta);
        header("Location: /TP_Final-PW2_UNLaM/view/editorHomeView.mustache");
        exit;
    }

    public function validation(){

        $this->presenter->render("view/validarUsuarioView.mustache");
    }

    public function activar()
    {
        $username = $_POST["username"] ?? "";
        $codigo = $_POST["codigo"] ?? "";

        $esValido = $this->modelUser->validarCodigo($username, $codigo);
        if ($esValido){
            $exito = "Validacion correcta, ya puedes iniciar sesion";
            $this->presenter->render("view/iniciarSesionView.mustache", ["exito" => $exito]);

        } else {
            $error = "Codigo incorrecto";
            $this->presenter->render("view/validarUsuarioView.mustache", ["error" => $error]);
        }
    }
}