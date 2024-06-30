<?php
class AdminModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getActivePlayers()
    {
        $result = $this->database->query_for_one("
            SELECT COUNT(*) AS total_jugadores
            FROM usuarios
            WHERE rol = 'J' AND activo = 1
        ");

        return $result['total_jugadores'] ?? 0;
    }

    public function getNewPlayers()
    {
        $fecha=$this->getDateFilterCondition('day');

        $result = $this->database->query_for_one("
            SELECT COUNT(*) AS nuevos_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1 AND $fecha
        ");

        return $result['nuevos_usuarios'] ?? 0;
    }

    public function getPlayersCreated($dateFilter)
    {
        $condition = $this->getDateFilterCondition($dateFilter);
        $query = "
        SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') as fecha, COUNT(*) as total
        FROM usuarios
        WHERE rol = 'J' AND activo = 1";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $query .= "
            GROUP BY DATE_FORMAT(fecha_creacion, '%Y-%m')
            ORDER BY fecha ASC;";

        $result = $this->database->query($query);
        return $result;
    }       

    public function getTotalGames()
    {
        $result = $this->database->query_for_one("
            SELECT COUNT(*) AS total_partidas
            FROM partidas
        ");

        return $result['total_partidas'] ?? 0;
    }

    public function getGamescreated($dateFilter)
    {
        $condition = $this->getDateFilterCondition($dateFilter);

        $query = "
            SELECT DATE_FORMAT(fecha_creacion, '%Y-%m') as fecha, COUNT(*) as total
            FROM partidas";

        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }

        $query .= "
            GROUP BY DATE_FORMAT(fecha_creacion, '%Y-%m')
            ORDER BY fecha ASC";

        $result = $this->database->query($query);
        return $result;
    }

    public function getTotalQuestions($dateFilter = '')
    {
        $condition = $this->getDateFilterCondition($dateFilter);
        $query = "
            SELECT COUNT(*) AS total_preguntas
            FROM preguntas
            WHERE estado = 'activa'
        ";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $result = $this->database->query_for_one($query);

        return $result['total_preguntas'] ?? 0;
    }

    public function getTotalCreatedQuestions($dateFilter = '')
    {
        $condition = $this->getDateFilterCondition($dateFilter);
        $query = "
            SELECT COUNT(*) AS total_preguntas_creadas
            FROM preguntas
            WHERE estado = 'sugerida'
        ";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $result = $this->database->query_for_one($query);

        return $result['total_preguntas_creadas'] ?? 0;
    }

    /*$query = "
    SELECT 
        u.nombre_usuario,
        COUNT(DISTINCT jp.id_partida) AS total_partidas,
        COUNT(DISTINCT pp.id_pregunta) AS total_preguntas_respondidas,
        (SUM(pp.id_pregunta) - SUM(DISTINCT jp.id_partida)) AS total_respuestas_correctas,
        (SUM(pp.id_pregunta) - SUM(DISTINCT jp.id_partida)) / COUNT(DISTINCT pp.id_pregunta)) * 100 AS porcentaje_correctas
    FROM usuarios u
    LEFT JOIN jugadores_partidas jp ON u.id = jp.id_jugador
    LEFT JOIN partidas_preguntas pp ON jp.id_partida = pp.id_partida
    WHERE u.rol = 'J'
";*/
    public function getPercentageOfCorrectAnswers($dateFilter) //(SUM(pp.id_pregunta) - 1) / COUNT(DISTINCT pp.id_pregunta) * 100 AS porcentaje_correctas
    {
        $condition = $this->getDateFilterConditionPorcentaje($dateFilter);
    
        $query = "
            SELECT 
                u.nombre_usuario,
                COUNT(DISTINCT jp.id_partida) AS total_partidas,
                COUNT(DISTINCT pp.id_pregunta) AS total_preguntas_respondidas,
                SUM(r.estado = 1) AS total_respuestas_correctas,
                (SUM(r.estado = 1) / COUNT(DISTINCT pp.id_pregunta)) * 100 AS porcentaje_correctas
            FROM usuarios u
            LEFT JOIN jugadores_partidas jp ON u.id = jp.id_jugador
            LEFT JOIN partidas_preguntas pp ON jp.id_partida = pp.id_partida
            LEFT JOIN respuestas r ON pp.id_pregunta = r.id_pregunta
            WHERE u.rol = 'J'
         ";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $query .= "
            GROUP BY u.nombre_usuario
            ORDER BY porcentaje_correctas DESC
            LIMIT 10
        ";
        
        $result = $this->database->query($query);
        return $result;
    }

    public function getUsersByCountry($dateFilter)
    {
        $condition = $this->getDateFilterCondition($dateFilter);
        $query = "
            SELECT pais, COUNT(*) as cantidad_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1
        ";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $query .= "
            GROUP BY pais;
        ";

        $result = $this->database->query($query);

        return $result;
    }

    public function getUsersBySex($dateFilter)
    {
        $condition = $this->getDateFilterCondition($dateFilter);
        $query = "
            SELECT sexo, COUNT(*) as cantidad_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1
        ";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $query .= "
            GROUP BY sexo;
        ";

        $result = $this->database->query($query);

        return $result;
    }

    public function getUsersByAgeGroup($dateFilter)
    {
        $condition = $this->getDateFilterCondition($dateFilter);
        $query = "
            SELECT 
                CASE 
                    WHEN YEAR(CURDATE()) - year_birth < 18 THEN 'Menores de Edad'
                    WHEN YEAR(CURDATE()) - year_birth >= 18 AND YEAR(CURDATE()) - year_birth < 65 THEN 'Adultos'
                    ELSE 'Jubilados'
                END AS grupo_etario,
                COUNT(*) as cantidad_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1
        ";

        if (!empty($condition)) {
            $query .= " AND $condition";
        }

        $query .= "
            GROUP BY grupo_etario;
        ";

        $result = $this->database->query($query);

        return $result;
    }

    private function getDateFilterCondition($dateFilter)
    {
        switch ($dateFilter) {
            case 'day':
                return "DATE(fecha_creacion) = CURRENT_DATE";
            case 'week':
                return "YEARWEEK(fecha_creacion) = YEARWEEK(CURRENT_DATE)";
            case 'month':
                return "MONTH(fecha_creacion) = MONTH(CURRENT_DATE) AND YEAR(fecha_creacion) = YEAR(CURRENT_DATE)";
            case 'year':
                return "YEAR(fecha_creacion) = YEAR(CURRENT_DATE)";
            default:
                return "";
        }
    }

    private function getDateFilterConditionPorcentaje($dateFilter)
    {
        $currentDate = date('Y-m-d');
        
        switch ($dateFilter) {
            case 'day':
                return "DATE(p.fecha_creacion) = '$currentDate'";
            case 'week':
                return "YEARWEEK(p.fecha_creacion) = YEARWEEK('$currentDate')";
            case 'month':
                return "MONTH(p.fecha_creacion) = MONTH('$currentDate') AND YEAR(p.fecha_creacion) = YEAR('$currentDate')";
            case 'year':
                return "YEAR(p.fecha_creacion) = YEAR('$currentDate')";
            default:
                return "";  // Tratar otros casos o valores por defecto según sea necesario
        }
    }
    
}