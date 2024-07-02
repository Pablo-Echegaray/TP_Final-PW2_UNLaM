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

           // isset($_SESSION["time"])   // medieron F5


        // Verifica si la página fue recargada
        if (isset($_SESSION['page_reloaded']) && $_SESSION['page_reloaded'] === true) {
            $this->finishGame();
            return;
        }

        // Marca la página como recargada
        $_SESSION['page_reloaded'] = true;

        $game = $this->model->playTheGame($usuario);
$_SESSION["time"]= now();

        $this->presenter->render("view/jugarView.mustache", $game);
    }

    public function checkAnswer()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }
//chequear el time del back $_SESSION["time"] - now() < 15 segundos
        $respuestaUsuario = $_POST['respuesta'];
        $usuario = $_SESSION["usuario"];
        $validatedQuestion = $this->model->checkAnswer($usuario, $respuestaUsuario);
        $actionGame = $validatedQuestion['actionGame'];
        unset($_SESSION["time"]);    
        // Resetea el indicador de recarga de página cuando se responde una pregunta
        //$_SESSION['page_reloaded'] = false;

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
        // Resetea el indicador de recarga de página al finalizar el juego
        unset($_SESSION['page_reloaded']);

        $this->presenter->render("view/finalizarPartidaView.mustache", ["puntaje" => $puntaje]);
    }

    public function timerRefresh()
    {
        if (!isset($_SESSION["usuario"])) {
            header('Location: http://localhost/TP_Final-PW2_UNLaM/user/get');
            exit();
        }

        $score_and_error = $this->model->timerRefresh();

        //Resetea el indicador de recarga de página al finalizar el juego
        //unset($_SESSION['page_reloaded']);

        $this->presenter->render("view/finalizarPartidaView.mustache", $score_and_error);
    }
}
