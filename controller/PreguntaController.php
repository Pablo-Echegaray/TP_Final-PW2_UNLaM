<?php
class PreguntaController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function suggestQuestion()
    {
        if (isset($_SESSION["usuario"])) {
            $categorias = $this->model->getCategorias();
            $this->presenter->render("view/crearPregunta.mustache", ["usuario" => $_SESSION["usuario"], "categorias" => $categorias]);
        } else {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
    }

    public function createQuestion()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
        echo "crear pregunta";
        $question = $_POST['pregunta'];
        $categoriaId = $_POST['categoria'];

        $rol = $_SESSION["usuario"][0]["rol"];

        if ($rol == "E") {
            $this->model->createQuestionEditor($question, $categoriaId);
            header("Location: /TP_Final-PW2_UNLaM/view/editorHomeView.mustache");
            exit;
        }

        if ($rol == "J") {
            $this->model->createQuestion($question, $categoriaId);
            header("Location: /TP_Final-PW2_UNLaM/view/homeView.mustache");
            exit;
        }
    }

    public function reportQuestion()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
        if (isset($_POST['preguntaId'])) {
            $preguntaId = $_POST['preguntaId'];
            $this->model->reportarPregunta($preguntaId);
            header("Location: /TP_Final-PW2_UNLaM/partida/play");
            exit;
        } else {
            echo "Error al reportar la pregunta";
            exit;
        }
    }
}