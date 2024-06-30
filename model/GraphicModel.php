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

    public function playersGraph($query)
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

        $graph = new Graph(800, 600);
        $graph->SetScale('textlin');

        $graph->title->Set('JUGADORES REGISTRADOS');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);

        $lineplot = new LinePlot($cantJugadores);
        $lineplot->SetColor('blue');
        $lineplot->SetWeight(2);

        $graph->Add($lineplot);
        $graph->xaxis->title->Set('Mes');
        $graph->yaxis->title->Set('Cantidad');
        $graph->xaxis->SetTickLabels($mesesLabel);

        $graph->Stroke();
    }

    public function gamesGraph($query)
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
    
        $graph = new Graph(800, 600);
        $graph->SetScale('textlin');
    
        $graph->title->Set('PARTIDAS JUGADAS');
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
    
        $lineplot = new LinePlot($cantPartidas);
        $lineplot->SetColor('blue');
        $lineplot->SetWeight(2);
    
        $graph->Add($lineplot);
        $graph->xaxis->title->Set('Mes');
        $graph->yaxis->title->Set('Cantidad');
        $graph->xaxis->SetTickLabels($mesesLabel);
    
        $graph->Stroke();
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
    
        $graph->Stroke();
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
    
        $graph->Stroke();
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
    
        $graph->Stroke();
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
    
        $graph->Stroke();
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
    
        $graph->Stroke();
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