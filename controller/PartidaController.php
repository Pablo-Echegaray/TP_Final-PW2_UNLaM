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

        if (isset($_SESSION["time"])) {
            // Se termina el juego cuando recarga la pagina
            $this->finishGame();
            return;
        }

        $game = $this->model->playTheGame($usuario);
        $_SESSION["time"] = time(); // Agarra el tiempo actual

        $this->presenter->render("view/jugarView.mustache", $game);
    }

    public function checkAnswer()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }

        $currentTime = time();
        if (isset($_SESSION["time"]) && ($currentTime - $_SESSION["time"]) > 15) {
            // Si pasa el tiempo tmb termina la partida
            $this->finishGame();
            return;
        }

        $respuestaUsuario = $_POST['respuesta'];
        $usuario = $_SESSION["usuario"];
        $validatedQuestion = $this->model->checkAnswer($usuario, $respuestaUsuario);
        $actionGame = $validatedQuestion['actionGame'];

        unset($_SESSION["time"]);

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
        $puntaje = $this->model->getPuntajeJugadorEnPartida($lastquestion["id_partida"]);
        $this->model->endGame($lastquestion['id_partida']);

        //Borramos la sesion time
        unset($_SESSION["time"]);

        $this->presenter->render("view/finalizarPartidaView.mustache", ["puntaje" => $puntaje]);
    }

    public function timerRefresh()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }

        $score_and_error = $this->model->timerRefresh();

        $this->presenter->render("view/finalizarPartidaView.mustache", $score_and_error);
    }
}
