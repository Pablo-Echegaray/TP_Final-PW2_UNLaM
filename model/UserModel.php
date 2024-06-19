<?php
class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtener($user, $pass)
    {
        return $this->database->query(
            "SELECT * 
        FROM usuarios
        WHERE nombre_usuario = '$user' AND password = '$pass'"
        );
    }

    public function getUserById($id)
    {
        return $this->database->query(
            "SELECT * 
        FROM usuarios
        WHERE id = '$id'"
        );
    }

    // Funciones editor

    public function getQuestion($idPregunta)
    {
        return $this->database->query(
            "SELECT *
        FROM preguntas
        WHERE id = '$idPregunta'"
        );
    }


    public function getAnswers($idPregunta)
    {
        return $this->database->query(
            "SELECT descripcion
        FROM respuestas
        WHERE id_pregunta = '$idPregunta'
        "
        );
    }

    public function getCategorias()
    {
        return $this->database->query(
            "SELECT * 
            FROM categorias"
        );
    }
    public function approveQuestion($idPregunta)
    {
        $this->database->execute("UPDATE preguntas SET estado = 'activa' WHERE id = $idPregunta");
    }

    public function disapproveQuestion($idPregunta)
    {
        $this->database->execute("UPDATE preguntas SET estado = 'inactiva' WHERE id = $idPregunta");
    }

    public function deleteAnswersForQuestion($idPregunta)
    {
        $this->database->execute("DELETE FROM respuestas WHERE id_pregunta = $idPregunta");
    }

    public function deleteQuestion($idPregunta)
    {
        $this->database->execute("DELETE FROM preguntas WHERE id = $idPregunta");
    }

    public function getQuestionsAndAnswers($estado)
    {
        $query = "SELECT p.id AS pregunta_id, p.descripcion AS pregunta, c.descripcion AS categoria,
                r.id AS respuesta_id, r.descripcion AS respuesta
              FROM preguntas p
              INNER JOIN categorias c ON p.id_categoria = c.id
              LEFT JOIN respuestas r ON p.id = r.id_pregunta
              WHERE p.estado = '$estado'";

        $result = $this->database->query($query);

        if ($result && is_array($result)) {
            $preguntas = [];
            foreach ($result as $row) {
                $pregunta_id = $row['pregunta_id'];
                if (!isset($preguntas[$pregunta_id])) {
                    $preguntas[$pregunta_id] = [
                        "id" => $pregunta_id,
                        "descripcion" => $row['pregunta'],
                        "categoria" => $row['categoria'],
                        "respuestas" => []
                    ];
                }
                if ($row['respuesta_id']) {
                    $preguntas[$pregunta_id]['respuestas'][] = [
                        "id" => $row['respuesta_id'],
                        "descripcion" => $row['respuesta']
                    ];
                }
            }
            return array_values($preguntas);
        } else {
            return [];
        }
    }

}