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
        $modo = "single player";//ejemplo
        $partida = $this->model->crearPartida($modo);//como hago para q deje de seguir creando partidaas??

        // OBTENER PREGUNTA ALEATORIA
        $pregunta = $this->model->getPreguntaRandom();

        // REGISTRAR PREGUNTA A PARTIDA
        $partidaPregunta = $this->model->asignarPreguntaAPartida($partida[0]["id"], $pregunta[0]["id"]);


        // OBTENER RESPUESTAS
        $respuestas = $this->model->getRespuestas($pregunta[0]["id"]);

        // VISTA
        $this->presenter->render("view/jugarView.mustache", ["usuario" => $_SESSION["usuario"], "preguntas" => $pregunta, "respuestas" => $respuestas]);
    }

    public function checkAnswer()
    {
        //Como extraigo la preguntaaa
        $respuestaUsuario = $_POST['respuesta'];
        echo $respuestaUsuario;
        /*$respuestaCorrecta = $this->model->getRespuestaCorrecta();
        if ($respuestaUsuario === $respuestaCorrecta['descripcion']) {
            echo "Respuesta correcta";
            //$this->model->actualizarPuntaje();
            $this->play();
        } else {
            echo "Respuesta incorrecta";
        }*/
    }


}