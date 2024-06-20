<?php
class Answers
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function list()
    {
        $this->db->query("SELECT * FROM answers");
        return $this->db->registros();
    }

    public function listByID($id)
    {
        $this->db->query("SELECT * FROM answers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function create($datos)
    {
        try {
            // Verificar si el survey_id y el question_id existen
            $this->db->query('SELECT COUNT(*) AS count FROM survey_set WHERE id = :survey_id');
            $this->db->bind(':survey_id', $datos['survey_id']);
            $resultSurvey = $this->db->single();

            $this->db->query('SELECT COUNT(*) AS count FROM questions WHERE id = :question_id');
            $this->db->bind(':question_id', $datos['question_id']);
            $resultQuestion = $this->db->single();

            if ($resultSurvey['count'] > 0 && $resultQuestion['count'] > 0) {
                $this->db->query('INSERT INTO answers(answer, survey_id, question_id, date) VALUES (:answer, :survey_id, :question_id, :date)');
                $this->db->bind(':answer', $datos['answer']);
                $this->db->bind(':survey_id', $datos['survey_id']);
                $this->db->bind(':question_id', $datos['question_id']);
                $this->db->bind(':date', $datos['date']);

                if ($this->db->execute()) {
                    return [
                        'status' => true,
                        'message' => 'Respuesta creada exitosamente'
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => 'Error al insertar la respuesta'
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'survey_id o question_id no encontrado'
                ];
            }
        } catch (PDOException $e) {
            return [
                'status' => false,
                'message' => 'Error de base de datos: ' . $e->getMessage()
            ];
        }
    }

    public function update($datos, $id)
    {
        try {
            $this->db->query('UPDATE answers SET answer = :answer, survey_id = :survey_id, question_id = :question_id, date = :date WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':answer', $datos['answer']);
            $this->db->bind(':survey_id', $datos['survey_id']);
            $this->db->bind(':question_id', $datos['question_id']);
            $this->db->bind(':date', $datos['date']);

            return $this->db->execute();
        } catch (PDOException $e) {
            return 'Error de base de datos: ' . $e->getMessage();
        }
    }
}
?>
