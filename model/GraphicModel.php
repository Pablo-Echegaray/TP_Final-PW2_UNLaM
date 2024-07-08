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

    // Método para generar gráficos de jugadores según el filtro especificado
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
    
    // Genera un gráfico diario de jugadores registrados
    private function generateDailyGraph($query)
    {
        $jugadoresPorHora = [];
        $horasLabel = [];
        $cantJugadores = [];
    
        // Agrupa los datos por hora del día
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
    
        // Llama al método general para generar el gráfico
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
    
    // Genera un gráfico semanal de jugadores registrados
    private function generateWeeklyGraph($query)
    {
        $jugadoresPorDiaSemana = [0, 0, 0, 0, 0, 0, 0]; // Inicializa arreglo para días de la semana
        $diasSemanaLabel = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']; // Etiquetas de días
        $cantJugadores = [];
    
        // Agrupa los datos por día de la semana
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $diaSemana = date('w', strtotime($fecha)); // Obtiene el día de la semana
    
            $jugadoresPorDiaSemana[$diaSemana] += (int)$row['total'];
        }
    
        $cantJugadores = array_values($jugadoresPorDiaSemana);
    
        // Llama al método general para generar el gráfico
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
    
    // Genera un gráfico mensual de jugadores registrados
    private function generateMonthlyGraph($query)
    {
        $jugadoresPorDia = []; // Inicializa arreglo para días del mes
        $diasLabel = []; // Etiquetas de días
        $cantJugadores = [];
    
        // Agrupa los datos por día del mes
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $dayOfMonth = date('j', strtotime($fecha)); // Obtiene el día del mes
    
            if (!isset($jugadoresPorDia[$dayOfMonth])) {
                $jugadoresPorDia[$dayOfMonth] = 0;
                $diasLabel[] = "$dayOfMonth";
            }
    
            $jugadoresPorDia[$dayOfMonth] += (int)$row['total'];
        }
    
        $cantJugadores = array_values($jugadoresPorDia);
    
        // Llama al método general para generar el gráfico
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
    
    // Genera un gráfico anual de jugadores registrados
    private function generateYearlyGraph($query)
    {
        $jugadoresPorMes = []; // Inicializa arreglo para meses
        $mesesLabel = []; // Etiquetas de meses
        $cantJugadores = [];
    
        // Agrupa los datos por mes del año
        foreach ($query as $row) {
            $fecha = $row['fecha'];
            $yearMonth = date('Y-m', strtotime($fecha)); // Obtiene el año y mes
            list($year, $month) = explode('-', $yearMonth); // Divide el formato "YYYY-MM"
    
            if (!isset($jugadoresPorMes[$year][$month])) {
                $jugadoresPorMes[$year][$month] = 0;
            }
    
            $jugadoresPorMes[$year][$month] += (int)$row['total'];
        }
    
        // Formatea los meses y cantidades para el gráfico
        foreach ($jugadoresPorMes as $year => $meses) {
            foreach ($meses as $month => $cantidad) {
                $mesNombre = date('M', mktime(0, 0, 0, $month, 1)); // Obtiene el nombre del mes
                $mesesLabel[] = "$mesNombre $year"; // Formato "NombreMes Año"
                $cantJugadores[] = $cantidad; // Cantidad de jugadores
            }
        }
    
        // Llama al método general para generar el gráfico
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
    
    // Método general para generar gráficos
    private function generateGraph($query, $title, $xTitle, $yTitle, $xLabels, $data, $graphType)
    {
        $graph = new Graph(800, 600); // Crea un objeto gráfico con dimensiones 800x600
        $graph->SetScale('textlin'); // Establece la escala lineal para los ejes
    
        $graph->title->Set($title); // Establece el título del gráfico
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14); // Configura la fuente del título
    
        // Selecciona el tipo de gráfico según el tipo especificado
        switch ($graphType) {
            case 'bar':
                $plot = new BarPlot($data); // Crea un gráfico de barras con los datos
                $plot->SetFillColor('blue'); // Establece el color de relleno de las barras
                break;
            case 'line':
            default:
                $plot = new LinePlot($data); // Crea un gráfico de líneas con los datos
                $plot->SetColor('blue'); // Establece el color de las líneas
                $plot->SetWeight(2); // Establece el grosor de las líneas
                break;
        }
    
        $graph->Add($plot); // Agrega el gráfico al objeto gráfico principal
        $graph->xaxis->title->Set($xTitle); // Establece el título del eje X
        $graph->yaxis->title->Set($yTitle); // Establece el título del eje Y
        $graph->xaxis->SetTickLabels($xLabels); // Establece las etiquetas del eje X
    
        $graph->Stroke(_IMG_HANDLER); // Genera el gráfico en memoria
    
        $fileName = "./public/image/charts/players_graph.png"; // Nombre de archivo para guardar el gráfico
    
        // Si el archivo ya existe, lo elimina para evitar conflictos
        if (file_exists($fileName)) {
            unlink($fileName);
        }
    
        // Guarda el gráfico como imagen PNG en el servidor
        $graph->img->Stream($fileName);
    
        // Configura las cabeceras y envía el gráfico al navegador
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
        // Prepara los datos y etiquetas para el gráfico de barras
        $data = array($activeQuestions, $createdQuestions); // Datos de preguntas activas y creadas
        $labels = array("Preguntas Activas", "Preguntas Creadas"); // Etiquetas para el eje X
        
        // Crea un objeto gráfico con dimensiones 800x600
        $graph = new Graph(800, 600);
        $graph->SetScale("textlin"); // Establece la escala lineal para los ejes
        
        // Crea un gráfico de barras con los datos preparados
        $barplot = new BarPlot($data);
        $barplot->value->Show(); // Muestra los valores de las barras
        $barplot->value->SetColor("black"); // Color del texto de los valores
        
        $graph->Add($barplot); // Agrega el gráfico de barras al objeto gráfico principal
        
        // Configura el título del gráfico
        $graph->title->Set("PREGUNTAS");
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14); // Configura la fuente del título
        
        // Configura las etiquetas del eje X y el título del eje Y
        $graph->xaxis->SetTickLabels($labels); // Establece las etiquetas del eje X
        $graph->yaxis->title->Set("Cantidad"); // Establece el título del eje Y
        
        // Genera el gráfico en memoria
        $graph->Stroke(_IMG_HANDLER);
    
        $fileName = "./public/image/charts/questions_graph.png";
        
        // Elimina el archivo existente para evitar conflictos
        if (file_exists($fileName)) {
            unlink($fileName);
        }
        
        // Guarda el gráfico como imagen PNG en el servidor
        $graph->img->Stream($fileName);
        
        // Configura las cabeceras y envía el gráfico al navegador
        $graph->img->Headers();
        $graph->img->Stream();
    }
    
    public function percentageOfCorrectAnswersGraph($query)
    {
        // Prepara los datos y etiquetas utilizando el método prepareDataAndLabels
        $result = $this->prepareDataAndLabels($query, 'porcentaje_correctas', 'nombre_usuario');
        
        // Crea un nuevo objeto Graph con dimensiones 800x600 píxeles
        $graph = new Graph(800, 600);
        $graph->SetScale('textlin'); // Establece la escala del gráfico como lineal con texto
    
        // Crea un objeto BarPlot para representar los datos como barras
        $barplot = new BarPlot($result['data']);
        $barplot->SetFillColor('blue'); // Establece el color de relleno de las barras como azul
        $barplot->SetColor('navy'); // Establece el color del borde de las barras como navy (azul oscuro)
        $barplot->SetWidth(0.6); // Establece el ancho de las barras en 0.6 unidades
        
        // Añade el BarPlot al objeto Graph
        $graph->Add($barplot);
    
        // Configuración del eje X (horizontal)
        $graph->xaxis->SetTickLabels($result['labels']); // Establece las etiquetas del eje X
        $graph->xaxis->SetFont(FF_ARIAL, FS_NORMAL, 9); // Establece la fuente del eje X
    
        // Configuración del eje Y (vertical)
        $graph->yaxis->SetLabelFormat('%d%%'); // Establece el formato de las etiquetas del eje Y como porcentaje
        $graph->yaxis->SetFont(FF_ARIAL, FS_NORMAL, 9); // Establece la fuente del eje Y
    
        // Configuración del título del gráfico
        $graph->title->Set('PORCENTAJE DE RESPUESTAS CORRECTAS POR USUARIO'); // Establece el título del gráfico
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14); // Establece la fuente del título
    
        // Configuración de los títulos de los ejes
        $graph->xaxis->title->Set('Jugadores'); // Título del eje X
        $graph->yaxis->title->Set('Porcentaje'); // Título del eje Y
    
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
        // Prepara los datos y etiquetas utilizando el método prepareDataAndLabels
        $result = $this->prepareDataAndLabels($query, 'cantidad_usuarios', 'sexo');
    
        // Crea un nuevo objeto PieGraph con dimensiones 800x600 píxeles
        $graph = new PieGraph(800, 600);
        $graph->SetShadow(); // Activa la sombra para el gráfico de torta
    
        // Crea un objeto PiePlot para representar los datos como gráfico de torta
        $pieplot = new PiePlot($result['data']);
    
        // Establece las leyendas para cada sección del gráfico de torta
        $pieplot->SetLegends($result['labels']);
    
        // Configura la visualización de los valores en las secciones del gráfico de torta
        $pieplot->value->Show(); // Muestra los valores de cada sección
        $pieplot->SetCenter(0.4); // Establece el centro del gráfico de torta
    
        // Añade el PiePlot al objeto PieGraph
        $graph->Add($pieplot);
    
        // Configuración del título y leyenda
        $graph->title->Set("USUARIOS POR SEXO"); // Título del gráfico
        $graph->title->SetFont(FF_ARIAL, FS_BOLD, 14); // Fuente del título
        $graph->legend->Pos(0.5, 0.9); // Posición de la leyenda en el gráfico
    
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
    
    private function prepareDataAndLabels($query, $dataKey, $labelKey)
    {
        $data = [];     // Array para almacenar los datos numéricos
        $labels = [];   // Array para almacenar las etiquetas o nombres
    
        // Iterar sobre cada fila del resultado de la consulta
        foreach ($query as $row) {
            // Obtener el dato numérico y convertirlo a entero
            $data[] = (int)$row[$dataKey];
            
            // Obtener la etiqueta o nombre asociado
            $labels[] = $row[$labelKey];
        }
    
        // Devolver un array asociativo con los datos y etiquetas preparados para la gráfica
        return ['data' => $data, 'labels' => $labels];
    }
}