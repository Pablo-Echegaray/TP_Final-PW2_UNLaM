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

    public function getTotalGames()
    {
        $result = $this->database->query_for_one("
            SELECT COUNT(*) AS total_partidas
            FROM partidas
        ");

        return $result['total_partidas'] ?? 0;
    }

    public function getTotalQuestions()
    {
        $result = $this->database->query_for_one("
            SELECT COUNT(*) AS total_preguntas
            FROM preguntas
            WHERE estado = 'activa'
        ");

        return $result['total_preguntas'] ?? 0;
    }

    public function getTotalCreatedQuestions()
    {
        $result = $this->database->query_for_one("
            SELECT COUNT(*) AS total_preguntas_creadas
            FROM preguntas
            WHERE estado = 'sugerida'
        ");

        return $result['total_preguntas_creadas'] ?? 0;
    }

    /*public function getUsuariosNuevos()
    {
        $this->database->execute("
            SELECT DATE_FORMAT(fecha_creacion, '%Y-%m-%d') AS fecha,
            COUNT(*) AS nuevos_usuarios
            FROM usuarios
            WHERE activo = 1
            GROUP BY DATE_FORMAT(fecha_creacion, '%Y-%m-%d');
        ");
    }

    public function CantidadDeUsuariosPorPais($pais)
    {
        $this->database->execute("
            SELECT pais, COUNT(*) as cantidad_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1 AND pais = $pais
            //GROUP BY pais;
        ");
    }

    public function CantidadDeUsuariosPorSexo()
    {
        $this->database->execute("
            SELECT pais, COUNT(*) as cantidad_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1
            GROUP BY sexo;
        ");
    }

    public function CantidadDeUsuariosPorGrupoEtario()
    {
        $this->database->execute("
            SELECT 
                CASE 
                    WHEN YEAR(CURDATE()) - year_birth < 18 THEN 'Menores de Edad'
                    WHEN YEAR(CURDATE()) - year_birth >= 18 AND YEAR(CURDATE()) - year_birth < 65 THEN 'Adultos'
                    ELSE 'Jubilados'
                END AS grupo_etario,
                COUNT(*) as cantidad_usuarios
            FROM usuarios
            WHERE rol = 'J' AND activo = 1
            GROUP BY grupo_etario;
        ");
    }*/
}