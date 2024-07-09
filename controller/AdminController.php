<?php
require_once("./helper/dompdf.php");
class AdminController
{
    private $presenter;
    private $modelAdmin;
    private $modelGraphic;

    public function __construct($modelAdmin, $modelGraphic, $presenter)
    {
        $this->presenter = $presenter;
        $this->modelAdmin = $modelAdmin;
        $this->modelGraphic = $modelGraphic;
    }
    
    public function players()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";

        $query = $this->modelAdmin->getPlayersCreated($dateFilter);

        $graph = $this->modelGraphic->playersGraph($query,$dateFilter);
    }    

    public function games()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";

        $query=$this->modelAdmin->getGamesCreated($dateFilter);

        $graph = $this->modelGraphic->gamesGraph($query,$dateFilter);
    }

    public function questions()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";
    
        $activeQuestions = $this->modelAdmin->getTotalQuestions($dateFilter);
        $createdQuestions = $this->modelAdmin->getTotalCreatedQuestions($dateFilter);
    
        $graph = $this->modelGraphic->questionsGraph($activeQuestions, $createdQuestions);
    }
    
    public function percentageOfCorrectAnswers()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";
    
        $query = $this->modelAdmin->getPercentageOfCorrectAnswers($dateFilter);

        $graph = $this->modelGraphic->percentageOfCorrectAnswersGraph($query);
    }
    
    public function usersByCountry()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";
    
        $query = $this->modelAdmin->getUsersByCountry($dateFilter);
    
        $graph = $this->modelGraphic->usersByCountryGraph($query);
    }
    
    public function usersBySex()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";
    
        $query = $this->modelAdmin->getUsersBySex($dateFilter);
    
        $graph = $this->modelGraphic->usersBySexGraph($query);
    }
    
    public function usersByAgeGroup()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";
    
        $query = $this->modelAdmin->getUsersByAgeGroup($dateFilter);
    
        $graph = $this->modelGraphic->usersByAgeGroupGraph($query);
    }

    public function generarPdf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['htmlGrafico'])) {
            $htmlGrafico = $_POST['htmlGrafico'];

            $pdfCreator = new PdfCreator;

            $playersPath = './public/image/charts/players_graph.png';
            $gamesPath = './public/image/charts/games_graph.png';
            $questionsPath = './public/image/charts/questions_graph.png';
            $rightAnswersPercentagePath = './public/image/charts/right_answers_percentage_graph.png';
            $usersByCountryPath = './public/image/charts/users_by_country_graph.png';
            $usersBySexPath = './public/image/charts/users_by_sex_graph.png';
            $usersByAgeGroupPath = './public/image/charts/users_by_age_group_graph.png';

            $playersBase64 = $this->imageToBase64($playersPath);
            $gamesBase64 = $this->imageToBase64($gamesPath);
            $questionsBase64 = $this->imageToBase64($questionsPath);
            $rightAnswersPercentageBase64 = $this->imageToBase64($rightAnswersPercentagePath);
            $usersByCountryBase64 = $this->imageToBase64($usersByCountryPath);
            $usersBySexBase64 = $this->imageToBase64($usersBySexPath);
            $usersByAgeGroupBase64 = $this->imageToBase64($usersByAgeGroupPath);

            $html = "
            <html>
            <head>
                <style>
                    .titulo {
                        display: flex;
                        text-align: center;
                        font-family: Arial, sans-serif;
                        color: #3f7faa;
                    }
                    .contenedor-grafico {
                        margin: 50px 20px;
                    }
                    .contenedor-grafico img {
                        width: 100%;
                        height: auto;
                    }
                </style>
            </head>
            <body>
                <h2 class='titulo'>REPORTE DE GRÁFICOS<h2>

                <div class='contenedor-grafico'>
                    <img src='data:image/png;base64,{$playersBase64}'>
                    <img src='data:image/png;base64,{$gamesBase64}'>
                    <img src='data:image/png;base64,{$questionsBase64}'>
                    <img src='data:image/png;base64,{$rightAnswersPercentageBase64}'>
                    <img src='data:image/png;base64,{$usersByCountryBase64}'>
                    <img src='data:image/png;base64,{$usersBySexBase64}'>
                    <img src='data:image/png;base64,{$usersByAgeGroupBase64}'>
                </div>
            </body>
            </html>";

            $pdfCreator->create($html);
        }
    }

    function imageToBase64($imagePath) {
        $imageData = file_get_contents($imagePath);
        $imageBase64 = base64_encode($imageData);
        return $imageBase64;
    }
}