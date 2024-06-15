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
            $this->presenter->render("view/crearPregunta.mustache",[ "usuario" => $_SESSION["usuario"], "categorias" => $categorias]);
        }
    }

    public function createQuestion(){
        echo "crear pregunta";
        $question = $_POST['pregunta'];
        $categoriaId = $_POST['categoria'];
        $this->model->createQuestion($question, $categoriaId);
    }
}