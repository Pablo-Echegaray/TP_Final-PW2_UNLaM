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

        $question = $_POST['pregunta'];
        $categoriaId = $_POST['categoria'];

        $correcta = $_POST['correcta'];
        $estadoA = ($correcta === 'A') ? 1 : 0;
        $estadoB = ($correcta === 'B') ? 1 : 0;
        $estadoC = ($correcta === 'C') ? 1 : 0;
        $estadoD = ($correcta === 'D') ? 1 : 0;

        $answers = [
            ['respuesta' => $_POST['opcionA'], 'estado' => $estadoA],
            ['respuesta' => $_POST['opcionB'], 'estado' => $estadoB],
            ['respuesta' => $_POST['opcionC'], 'estado' => $estadoC],
            ['respuesta' => $_POST['opcionD'], 'estado' => $estadoD],
        ];

        $rol = $_SESSION["usuario"][0]["rol"];

        if ($rol == "E") {
            $id_pregunta = $this->model->createQuestionEditor($question, $categoriaId);
            if ($id_pregunta) {
                foreach ($answers as $answer) {
                    $this->model->createAnswer($question, $categoriaId, $answer['respuesta'], $answer['estado'], $id_pregunta);
                }
                header("Location: /TP_Final-PW2_UNLaM/view/editorHomeView.mustache");
                exit;
            }
        }

        if ($rol == "J") {
            $id_pregunta = $this->model->createQuestion($question, $categoriaId);
            if ($id_pregunta) {
                foreach ($answers as $answer) {
                    $this->model->createAnswer($question, $categoriaId, $answer['respuesta'], $answer['estado'], $id_pregunta);
                }
                header("Location: /TP_Final-PW2_UNLaM/view/homeView.mustache");
                exit;
            }
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