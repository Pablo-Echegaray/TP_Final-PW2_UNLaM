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

    public function createQuestion($question, $categoriaId){
        if ($this->validateIfQuestionExists($question)){
            echo "La pregunta ya existe";
        }
        else{
            $validatedQuestion = "¿".ucfirst($question)."?";
            echo $validatedQuestion;
            $this->database->execute(
                "INSERT INTO preguntados.preguntas (descripcion, estado, entregadas, hit, id_categoria)
                VALUES ('$validatedQuestion', 'sugerida', 100, 50, $categoriaId);"
            );
        }
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

}