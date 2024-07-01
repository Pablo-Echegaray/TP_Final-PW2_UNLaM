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
        $game = $this->model->playTheGame($usuario);
        $this->presenter->render("view/jugarView.mustache", $game);
    }

    public function checkAnswer()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
        //Como extraigo la preguntaaa
        $lastquestion = $this->model->getLastQuestionInGame();
        $idPartida = $lastquestion["id_partida"];
        $respuestaUsuario = $_POST['respuesta'];
        $respuestaCorrecta = $this->model->getRespuestaCorrecta($lastquestion["id_pregunta"]);
        $usuario = $_SESSION["usuario"];

        if ($respuestaUsuario == $respuestaCorrecta['descripcion']) {
            $mensaje= "RESPUESTA CORRECTA";
            $claseTexto = "texto-verde";
            $this->model->actualizarPuntaje($idPartida);
            $this->model->updateQuestionDeliveredAndHit($lastquestion["id_pregunta"], 1);
            $this->model->updateUserDeliveredAndHit($usuario[0]["id"], 1);
            //$this->presenter->render("view/mensajePartida.mustache", ["mensaje"=>$mensaje, "claseTexto"=>$claseTexto]);
            $this->presenter->render("view/mensajePartidaView.mustache", ["mensaje"=>$mensaje, "claseTexto"=>$claseTexto]);
            header('Refresh: 2; URL=/TP_Final-PW2_UNLaM/partida/play');
        } else {
            $this->model->endGame($idPartida);
            $this->model->updateQuestionDeliveredAndHit($lastquestion["id_pregunta"], -1);
            $this->model->updateUserDeliveredAndHit($usuario[0]["id"], -1);
            $mensaje= "RESPUESTA INCORRECTA";
            $claseTexto = "texto-rojo";
            $this->presenter->render("view/mensajePartidaView.mustache", ["mensaje"=>$mensaje, "claseTexto"=>$claseTexto]);
            header('Refresh: 2; URL=/TP_Final-PW2_UNLaM/partida/finishGame');
        }
    }

    public function finishGame()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
        $lastquestion = $this->model->getLastQuestionInGame();
        $puntaje=$this->model->getPuntajeJugadorEnPartida($lastquestion["id_partida"]);
        $this->presenter->render("view/finalizarPartidaView.mustache", ["puntaje"=>$puntaje]);
    }

    public function timerRefresh()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }

        $lastquestion = $this->model->getLastQuestionInGame();
        $idPartida = $lastquestion["id_partida"];

        // Cambiar el estado de la partida a "finished"
        $this->model->endGame($idPartida);

        $puntaje = $this->model->getPuntajeJugadorEnPartida($idPartida);

        $error = "Tiempo agotado";
        $this->presenter->render("view/finalizarPartidaView.mustache", ["puntaje" => $puntaje, "error" => $error]);
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