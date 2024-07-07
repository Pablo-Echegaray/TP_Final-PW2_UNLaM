<?php
require_once ("./helper/jpgraph/src/jpgraph.php");
require_once ("./helper/jpgraph/src/jpgraph_bar.php");
require_once ("./helper/jpgraph/src/jpgraph_line.php");
require_once ("./helper/jpgraph/src/jpgraph_pie.php");

class GraphicModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function playersGraph($query, $filtro)
    {
        switch ($filtro) {
            case 'day':
                return $this->generateDailyGraph($query);
            case 'week':
                return $this->generateWeeklyGraph($query);
            case 'month':
                return $this->generateMonthlyGraph($query);
            case 'year':
                return $this->generateYearlyGraph($query);
            default:
                return $this->generateYearlyGraph($query);
        }
    }
    
    private function generateDailyGraph($query)
    {
        $jugadoresPorHora = [];
        $horasLabel = [];
        $cantJugadores = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $hora = date('H', strtotime($fecha));
    
            if (!isset($jugadoresPorHora[$hora])) {
                $jugadoresPorHora[$hora] = 0;
                $horasLabel[] = "$hora:00";
            }
    
            $jugadoresPorHora[$hora] += (int)$row['total'];
        }
    
        $cantJugadores = array_values($jugadoresPorHora);
    
        $this->generateGraph(
            $query,
            "JUGADORES REGISTRADOS",
            "Hora del día",
            "Cantidad",
            $horasLabel,
            $cantJugadores,
            'bar'
        );
    }
    
    private function generateWeeklyGraph($query)
    {
        $jugadoresPorDiaSemana = [0, 0, 0, 0, 0, 0, 0];
        $diasSemanaLabel = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $cantJugadores = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $diaSemana = date('w', strtotime($fecha));
    
            $jugadoresPorDiaSemana[$diaSemana] += (int)$row['total'];
        }
    
        $cantJugadores = array_values($jugadoresPorDiaSemana);
    
        $this->generateGraph(
            $query,
            "JUGADORES REGISTRADOS",
            "Día de la semana",
            "Cantidad",
            $diasSemanaLabel,
            $cantJugadores,
            'line'
        );
    }
    
    private function generateMonthlyGraph($query)
    {
        $jugadoresPorDia = [];
        $diasLabel = [];
        $cantJugadores = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $dayOfMonth = date('j', strtotime($fecha));
    
            if (!isset($jugadoresPorDia[$dayOfMonth])) {
                $jugadoresPorDia[$dayOfMonth] = 0;
                $diasLabel[] = "$dayOfMonth";
            }
    
            $jugadoresPorDia[$dayOfMonth] += (int)$row['total'];
        }
    
        $cantJugadores = array_values($jugadoresPorDia);
    
        $this->generateGraph(
            $query,
            "JUGADORES REGISTRADOS",
            "Día del mes",
            "Cantidad de jugadores",
            $diasLabel,
            $cantJugadores,
            'line'
        );
    }
    
    private function generateYearlyGraph($query)
    {
        $jugadoresPorMes = [];
        $mesesLabel = [];
        $cantJugadores = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $yearMonth = date('Y-m', strtotime($fecha));
            list($year, $month) = explode('-', $yearMonth);
    
            if (!isset($jugadoresPorMes[$year][$month])) {
                $jugadoresPorMes[$year][$month] = 0;
            }
    
            $jugadoresPorMes[$year][$month] += (int)$row['total'];
        }
    
        foreach ($jugadoresPorMes as $year => $meses) {
            foreach ($meses as $month => $cantidad) {
                $mesNombre = date('M', mktime(0, 0, 0, $month, 1));
                $mesesLabel[] = "$mesNombre $year";
                $cantJugadores[] = $cantidad;
            }
        }
    
        $this->generateGraph(
            $query,
            "JUGADORES REGISTRADOS",
            "Mes",
            "Cantidad",
            $mesesLabel,
            $cantJugadores,
            'line'
        );
    }    

    private function generateGraph($query, $title, $xTitle, $yTitle, $xLabels, $data, $graphType)
    {
        $graph = new Graph(800, 600);
        $graph->SetScale('textlin');
    
        $graph->title->Set($title);
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
    
        switch ($graphType) {
            case 'bar':
                $plot = new BarPlot($data);
                $plot->SetFillColor('blue');
                break;
            case 'line':
            default:
                $plot = new LinePlot($data);
                $plot->SetColor('blue');
                $plot->SetWeight(2);
                break;
        }
    
        $graph->Add($plot);
        $graph->xaxis->title->Set($xTitle);
        $graph->yaxis->title->Set($yTitle);
        $graph->xaxis->SetTickLabels($xLabels);
    
        $graph->Stroke(_IMG_HANDLER);
    
        $fileName = "./public/image/charts/players_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);
    
        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function gamesGraph($query, $filtro)
    {
        switch ($filtro) {
            case 'day':
                return $this->generateDailyGamesGraph($query);
            case 'week':
                return $this->generateWeeklyGamesGraph($query);
            case 'month':
                return $this->generateMonthlyGamesGraph($query);
            case 'year':
                return $this->generateYearlyGamesGraph($query);
            default:
                return $this->generateYearlyGamesGraph($query);
        }
    }
    
    private function generateDailyGamesGraph($query)
    {
        $partidasPorHora = [];
        $horasLabel = [];
        $cantPartidas = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $hora = date('H', strtotime($fecha));
    
            if (!isset($partidasPorHora[$hora])) {
                $partidasPorHora[$hora] = 0;
                $horasLabel[] = "$hora:00";
            }
    
            $partidasPorHora[$hora] += (int)$row['total'];
        }
    
        $cantPartidas = array_values($partidasPorHora);
    
        $this->generateGameGraph(
            "PARTIDAS JUGADAS",
            "Hora del día",
            "Cantidad",
            $horasLabel,
            $cantPartidas,
            'bar'
        );
    }    
    
    private function generateWeeklyGamesGraph($query)
    {
        $partidasPorDiaSemana = [0, 0, 0, 0, 0, 0, 0];
        $diasSemanaLabel = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        $cantPartidas = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $diaSemana = date('w', strtotime($fecha));
    
            $partidasPorDiaSemana[$diaSemana] += (int)$row['total'];
        }
    
        $cantPartidas = array_values($partidasPorDiaSemana);
    
        $this->generateGameGraph(
            "PARTIDAS JUGADAS",
            "Día de la semana",
            "Cantidad",
            $diasSemanaLabel,
            $cantPartidas,
            'line'
        );
    }
    
    private function generateMonthlyGamesGraph($query)
    {
        $partidasPorDia = [];
        $diasLabel = [];
        $cantPartidas = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $dayOfMonth = date('j', strtotime($fecha));
    
            if (!isset($partidasPorDia[$dayOfMonth])) {
                $partidasPorDia[$dayOfMonth] = 0;
                $diasLabel[] = "$dayOfMonth";
            }
    
            $partidasPorDia[$dayOfMonth] += (int)$row['total'];
        }
    
        $cantPartidas = array_values($partidasPorDia);
    
        $this->generateGameGraph(
            "PARTIDAS JUGADAS",
            "Día del mes",
            "Cantidad de partidas",
            $diasLabel,
            $cantPartidas,
            'line'
        );
    }
    
    private function generateYearlyGamesGraph($query)
    {
        $partidasPorMes = [];
        $mesesLabel = [];
        $cantPartidas = [];
    
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $yearMonth = date('Y-m', strtotime($fecha));
            list($year, $month) = explode('-', $yearMonth);
    
            if (!isset($partidasPorMes[$year][$month])) {
                $partidasPorMes[$year][$month] = 0;
            }
    
            $partidasPorMes[$year][$month] += (int)$row['total'];
        }
    
        foreach ($partidasPorMes as $year => $meses) {
            foreach ($meses as $month => $cantidad) {
                $mesNombre = date('M', mktime(0, 0, 0, $month, 1));
                $mesesLabel[] = "$mesNombre $year";
                $cantPartidas[] = $cantidad;
            }
        }
    
        $this->generateGameGraph(
            "PARTIDAS JUGADAS",
            "Mes",
            "Cantidad",
            $mesesLabel,
            $cantPartidas,
            'line'
        );
    }
    
    private function generateGameGraph($title, $xTitle, $yTitle, $xLabels, $data, $graphType)
    {
        $graph = new Graph(800, 600);
        $graph->SetScale('textlin');
    
        $graph->title->Set($title);
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
    
        switch ($graphType) {
            case 'bar':
                $plot = new BarPlot($data);
                $plot->SetFillColor('blue');
                break;
            case 'line':
            default:
                $plot = new LinePlot($data);
                $plot->SetColor('blue');
                $plot->SetWeight(2);
                break;
        }
    
        $graph->Add($plot);
        $graph->xaxis->title->Set($xTitle);
        $graph->yaxis->title->Set($yTitle);
        $graph->xaxis->SetTickLabels($xLabels);
    
        $graph->Stroke(_IMG_HANDLER);
    
        $fileName = "./public/image/charts/games_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);
    
        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function questionsGraph($activeQuestions, $createdQuestions)
    {
        $data = array($activeQuestions, $createdQuestions);
        $labels = array("Preguntas Activas", "Preguntas Creadas");
    
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin");
    
        $barplot = new BarPlot($data);
        $barplot->value->Show();
        $barplot->value->SetColor("black");
    
        $graph->Add($barplot);
    
        $graph->title->Set("PREGUNTAS");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
        $graph->xaxis->SetTickLabels($labels);
        $graph->yaxis->title->Set("Cantidad");
    
        $graph->Stroke(_IMG_HANDLER);

        $fileName = "./public/image/charts/questions_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);

        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function percentageOfCorrectAnswersGraph($query)
    {
        $result = $this->prepareDataAndLabels($query, 'porcentaje_correctas', 'nombre_usuario');
    
        $graph = new Graph(800, 600);
        $graph->SetScale('textlin');
    
        $barplot = new BarPlot($result['data']);
        $barplot->SetFillColor('blue');
        $barplot->SetColor('navy');
        $barplot->SetWidth(0.6);
    
        $graph->Add($barplot);
    
        $graph->xaxis->SetTickLabels($result['labels']);
        $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 9);
    
        $graph->yaxis->SetLabelFormat('%d%%');
        $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 9);
    
        $graph->title->Set('PORCENTAJE DE RESPUESTAS CORRECTAS POR USUARIO');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
        $graph->xaxis->title->Set('Jugadores');
        $graph->yaxis->title->Set('Porcentaje');
    
        $graph->Stroke(_IMG_HANDLER);

        $fileName = "./public/image/charts/right_answers_percentage_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);

        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function usersByCountryGraph($query)
    {
        $result = $this->prepareDataAndLabels($query, 'cantidad_usuarios', 'pais');
    
        $graph = new Graph(800, 600);
        $graph->SetScale('textlin');
    
        $bplot = new BarPlot($result['data']);
        $bplot->SetFillColor('blue');
        $bplot->value->Show();
        $bplot->value->SetFormat('%d');
        $bplot->value->SetFont(FF_ARIAL, FS_NORMAL, 12);
    
        $graph->xaxis->SetTickLabels($result['labels']);
    
        $graph->Add($bplot);
    
        $graph->title->Set("JUGADORES POR PAÍS");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
        $graph->xaxis->title->Set("Países");
        $graph->yaxis->title->Set("Cantidad de Jugadores");
        $graph->legend->Pos(0.5, 0.9);
    
        $graph->Stroke(_IMG_HANDLER);

        $fileName = "./public/image/charts/users_by_country_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);

        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function usersBySexGraph($query)
    {
        $result = $this->prepareDataAndLabels($query, 'cantidad_usuarios', 'sexo');
    
        $graph = new PieGraph(800, 600);
        $graph->SetShadow();
    
        $pieplot = new PiePlot($result['data']);
        $pieplot->SetLegends($result['labels']);
        $pieplot->value->Show();
        $pieplot->SetCenter(0.4);
    
        $graph->Add($pieplot);
        $graph->title->Set("USUARIOS POR SEXO");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
        $graph->legend->Pos(0.5, 0.9);
    
        $graph->Stroke(_IMG_HANDLER);

        $fileName = "./public/image/charts/users_by_sex_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);

        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function usersByAgeGroupGraph($query)
    {
        $result = $this->prepareDataAndLabels($query, 'cantidad_usuarios', 'grupo_etario');
    
        $graph = new PieGraph(800, 600);
        $graph->SetShadow();
    
        $pieplot = new PiePlot($result['data']);
        $pieplot->SetLegends($result['labels']);
        $pieplot->value->Show();
        $pieplot->SetCenter(0.4);
    
        $graph->Add($pieplot);
        $graph->title->Set("USUARIOS POR GRUPO ETARIO");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
        $graph->legend->Pos(0.5, 0.9);
    
        $graph->Stroke(_IMG_HANDLER);

        $fileName = "./public/image/charts/users_by_age_group_graph.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);

        $graph->img->Headers();
        $graph->img->Stream();
    }

    public function errorImg(){
        $graph = new CanvasGraph(800, 600);
        $graph->title->Set('Mensaje de Aviso');
        $graph->SetMargin(20, 20, 20, 20);

        // Agregar un cuadro de texto con el mensaje de aviso
        $text = new Text("El gráfico no está disponible temporalmente.\n\nInténtalo de nuevo más tarde.", 10, 100);
        $text->SetFont(FF_ARIAL, FS_NORMAL, 12);
        $graph->Add($text);

        $graph->Stroke(_IMG_HANDLER);

        $fileName = "./public/image/charts/error.png";
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        $graph->img->Stream($fileName);

        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    private function prepareDataAndLabels($query, $dataKey, $labelKey)
    {
        $data = [];
        $labels = [];
    
        foreach ($query as $row) {
            $data[] = (int)$row[$dataKey];
            $labels[] = $row[$labelKey];
        }
    
        return ['data' => $data, 'labels' => $labels];
    } 
}