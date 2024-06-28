<?php
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
}