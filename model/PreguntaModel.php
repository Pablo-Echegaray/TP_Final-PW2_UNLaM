<?php
class PreguntaModel
{
    private $database;
    private $dataConversion;

    public function __construct($database, $dataConversion)
    {
        $this->database = $database;
        $this->dataConversion = $dataConversion;
    }

    public function getCategorias(){
        return $this->database->query(
            "SELECT * 
             FROM categorias"
        );
    }

    public function getQuestions(){
        return $this->database->query(
            "SELECT * 
             FROM preguntas"
            );
    }

    public function getQuestionId($validatedQuestion){
        return $this->database->query(
            "SELECT id
             FROM preguntados.preguntas p
             WHERE p.descripcion = '$validatedQuestion'
             "
            );
    }

    public function createQuestion($question, $categoriaId){
        if ($this->validateIfQuestionExists($question)){
            echo "La pregunta ya existe";
        }
        else{
            $validatedQuestion = "¿".ucfirst($question)."?";
            
            $this->database->execute(
                "INSERT INTO preguntados.preguntas (descripcion, estado, entregadas, hit, id_categoria)
                VALUES ('$validatedQuestion', 'sugerida', 100, 50, $categoriaId);"
            );
            $preguntaId = $this->getQuestionId($validatedQuestion);
            return $preguntaId[0]["id"];
        }
    }

    public function createQuestionEditor($question, $categoriaId){
        if ($this->validateIfQuestionExists($question)){
            echo "La pregunta ya existe";
            return false;
        }
        else{
            $validatedQuestion = "¿".ucfirst($question)."?";
            $this->database->execute(
                "INSERT INTO preguntados.preguntas (descripcion, estado, entregadas, hit, id_categoria)
                VALUES ('$validatedQuestion', 'activa', 100, 50, $categoriaId);"
            );
            $preguntaId = $this->getQuestionId($validatedQuestion);
            return $preguntaId[0]["id"];
        }
    }

    public function createAnswer($question, $categoriaId, $respuesta, $estado, $creada){
        return $this->database->execute(
                "INSERT INTO preguntados.respuestas (descripcion, estado, id_pregunta)
                VALUES ('$respuesta', $estado, $creada);"
            );
        }
        
    public function editQuestionAndAnswers($idPregunta, $idCategoria, $pregunta, $answers, $correcta){
        $states = ["A"=> 0, "B"=> 1, "C"=> 2, "D"=> 3];
        foreach ($states as $key => $value) {
            if ($key == $correcta){
                $answers[$value]['estado'] = 1;
            }
            else{
                $answers[$value]['estado'] = 0;
            }

        }
        $this->updateQuestion($idPregunta, $pregunta, $idCategoria);

        for ($i=0; $i < count($answers); $i++){
            $this->updateAnswer($answers[$i]['id'],$answers[$i]['descripcion'], $answers[$i]['estado']);
        }
    }

    private function updateQuestion($idPregunta, $pregunta, $idCategoria){
        $this->database->execute(
            "UPDATE preguntas
             SET
                descripcion = '$pregunta',
                id_categoria = $idCategoria
             WHERE
                id = $idPregunta;"
        );
    }

    private function updateAnswer($idAnswer, $answer, $state){
        $this->database->execute(
            "UPDATE respuestas
             SET
                descripcion = '$answer',
                estado = $state
             WHERE
                id = $idAnswer;"
        );
    }

    private function validateIfQuestionExists($question): bool
    {
        $questions = $this->getQuestions();
        $newQuestion = strtolower($question);

        foreach($questions as $question){
            if($this->dataConversion->deleteSpecialCharacters(strtolower($question['descripcion'])) == $newQuestion){
                return true;
            }
        }
        return false;
    }

    public function reportarPregunta($preguntaId)
    {
        $this->database->execute(
            "UPDATE preguntas
            SET estado = 'reportada'
            WHERE id = $preguntaId
        ");
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
            "SELECT *
        FROM respuestas
        WHERE id_pregunta = '$idPregunta'
        "
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
                r.id AS respuesta_id, r.descripcion AS respuesta, r.estado AS estado_respuesta
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
                        "descripcion" => $row['respuesta'],
                        "estado_respuesta" => $row['estado_respuesta']
                    ];
                }
            }
            return array_values($preguntas);
        } else {
            return [];
        }
    }
}