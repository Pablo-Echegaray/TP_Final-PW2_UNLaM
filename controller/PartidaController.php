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
        if (isset($_SESSION["usuario"])) {
            $usuario = $_SESSION["usuario"];
            echo $usuario[0]["id"];
        }
        // CREAR LA PARTIDA
        $modo = "single player";//ejemplo
        $lastGame = $this->model->getLastGame();
        if ($lastGame == null || $lastGame["estado"] == "finished") {
            $this->model->crearPartida($modo);
            $game = $this->model->getLastGame();
            $this->model->asignarPartidaAJugador($usuario[0]["id"], $game["id"], 50);
        }
        // OBTENER PREGUNTA ALEATORIA
        $pregunta = $this->model->getPreguntaRandom($usuario[0]["id"]);
        $game = $this->model->getLastGame();
        // REGISTRAR PREGUNTA A PARTIDA
        $partidaPregunta = $this->model->asignarPreguntaAPartida($game["id"], $pregunta[0]["id"]);


        // OBTENER RESPUESTAS
        $respuestas = $this->model->getRespuestas($pregunta[0]["id"]);

        // VISTA
        $this->presenter->render("view/jugarView.mustache", ["usuario" => $_SESSION["usuario"], "preguntas" => $pregunta, "respuestas" => $respuestas]);
    }

    public function checkAnswer()
    {
        //Como extraigo la preguntaaa
        $lastquestion = $this->model->getLastQuestionInGame();
        $respuestaUsuario = $_POST['respuesta'];
        echo $respuestaUsuario;
        $respuestaCorrecta = $this->model->getRespuestaCorrecta($lastquestion["id_pregunta"]);
        if ($respuestaUsuario == $respuestaCorrecta['descripcion']) {
            echo "Respuesta correcta";
            //$this->model->actualizarPuntaje();
            $this->play();
        } else {
            $this->model->endGame($lastquestion["id_partida"]);
            echo "Respuesta incorrecta PERDISTE";
        }
    }


}