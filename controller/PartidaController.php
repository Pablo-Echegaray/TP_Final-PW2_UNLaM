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

        $respuestaUsuario = $_POST['respuesta'];
        $usuario = $_SESSION["usuario"];
        $validatedQuestion = $this->model->checkAnswer($usuario, $respuestaUsuario);
        $actionGame = $validatedQuestion['actionGame'];
        $this->presenter->render("view/mensajePartidaView.mustache", $validatedQuestion);
        header('Refresh: 2; URL=/TP_Final-PW2_UNLaM/partida/' . $actionGame);

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
    
}