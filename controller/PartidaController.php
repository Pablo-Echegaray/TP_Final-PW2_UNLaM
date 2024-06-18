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
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
        $usuario = $_SESSION["usuario"];
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

        //OBTENER CATEGORIA y ASIGNAR COLOR
        $categoria = $this->model->obtenerCategoriaPregunta($pregunta[0]["id"]);
        $color = self::obtenerColorPorCategoria($categoria[0]["descripcion"]);

        // REGISTRAR PREGUNTA A PARTIDA
        $partidaPregunta = $this->model->asignarPreguntaAPartida($game["id"], $pregunta[0]["id"]);

        // OBTENER RESPUESTAS
        $respuestas = $this->model->getRespuestas($pregunta[0]["id"]);

        // VISTA
        $this->presenter->render("view/jugarView.mustache", ["usuario" => $_SESSION["usuario"], "preguntas" => $pregunta, "respuestas" => $respuestas, "color" => $color]);
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

    private static function obtenerColorPorCategoria($descripcion)
    {
        $color = "";
        switch($descripcion){
            case "Geografía":
                $color = "#0487d9";
                break;
            case "Literatura":
                $color = "#7325a6";
                break;
            case "Deportes":
                $color = "#a1a61f";
                break;
            case "Ciencia":
                $color = "#f27141";
                break;
            case "Historia":
                $color = "#f2cb05";
                break;
            default:
                $color = "#f23568";
                break;
        }
        return $color;
    }
}