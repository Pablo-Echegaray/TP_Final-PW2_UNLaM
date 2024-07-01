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

        $graph = $this->modelGraphic->playersGraph($query);
    }    

    public function games()
    {
        $dateFilter = isset($_GET['filtro']) ? $_GET['filtro'] : "";

        $query=$this->modelAdmin->getGamesCreated($dateFilter);

        $graph = $this->modelGraphic->gamesGraph($query);
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
                    <img src='/public/image/charts/players_graph.png'>
                    <img src='/public/image/charts/games_graph.png'>
                    <img src='/public/image/charts/questions_graph.png'>
                    <img src='/public/image/charts/right_answers_percentage_graph.png'>
                    <img src='/public/image/charts/users_by_country_graph.png'>
                    <img src='/public/image/charts/users_by_sex_graph.png'>
                    <img src='/public/image/charts/users_by_age_group_graph.png'>
                </div>
            </body>
            </html>";

            $pdfCreator->create($html);
        }
    }
}