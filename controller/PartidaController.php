<?php
class PartidaController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }
    public function play()
    {

        // CREAR LA PARTIDA
        $modo = "";
        $partida = $this->model->add($modo);

        // OBTENER PREGUNTA ALEATORIA
        $pregunta = $this->model->getPreguntaRandom();

        // REGISTRAR PREGUNTA A PARTIDA
        // $this->model->asignarPreguntaAPartida();

        // OBTENER RESPUESTAS
        $respuestas = $this->model->getRespuestas();

        // VISTA
        $this->presenter->render("view/jugarView.mustache", ["usuario" => $_SESSION["usuario"], "preguntas" => $pregunta, "respuestas" => $respuestas]);
    }

    public function checkAnswer()
    { 
        $respuestaUsuario = $_POST['respuesta'];
        $respuestaCorrecta = $this->model->getRespuestaCorrecta();
       

        if ($respuestaUsuario === $respuestaCorrecta['descripcion']) {
            echo "Respuesta correcta";
            //$this->model->actualizarPuntaje();
            $this->play();
        } else {
            echo "Respuesta incorrecta";
        }

    }


}