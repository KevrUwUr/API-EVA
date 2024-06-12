<?php
// Definición de la clase Cliente
class Questions
{
    // Propiedad privada para la conexión a la base de datos
    private $db;

    // Constructor de la clase
    function __construct()
    {
        // Se instancia la clase Base para la conexión a la base de datos
        $this->db = new Base;
    }

    public function list()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes
        $this->db->query("SELECT * FROM questions");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listByID($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT * FROM questions WHERE id =  :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function create($datos)
    {
        try {
            // Verificar si el survey_id existe
            $this->db->query('SELECT COUNT(*) AS count FROM survey_set WHERE id = :survey_id');
            $this->db->bind(':survey_id', $datos['survey_id']); // Corregir el enlace del parámetro
            $result = $this->db->single();

            if ($result['count'] > 0) {
                // survey_id existe, proceder con la inserción
                $this->db->query('INSERT INTO questions (question, type, section, percentage, frm_option, conditional, id_conditional, conditional_answer, survey_id)
                    VALUES (:question, :type, :section, :percentage, :frm_option, :conditional, :id_conditional, :conditional_answer, :survey_id)');

                // Asignar valores a los parámetros
                $this->db->bind(':question', $datos['question']);
                $this->db->bind(':type', $datos['type']);
                $this->db->bind(':section', $datos['section']);
                $this->db->bind(':percentage', $datos['percentage']);
                $this->db->bind(':frm_option', $datos['frm_option']);
                $this->db->bind(':conditional', $datos['conditional']);
                $this->db->bind(':id_conditional', $datos['id_conditional']);
                $this->db->bind(':conditional_answer', $datos['conditional_answer']);
                $this->db->bind(':survey_id', $datos['survey_id']);

                // Ejecutar la consulta y retornar true si tiene éxito
                if ($this->db->execute()) {
                    return json_encode([
                        'status' => true,
                        'message' => 'Pregunta creada exitosamente'
                    ]);
                } else {
                    return json_encode([
                        'status' => false,
                        'message' => 'Error al insertar los datos'
                    ]);
                }
            } else {
                // survey_id no existe, retornar mensaje de error
                return json_encode([
                    'status' => false,
                    'message' => 'survey_id no encontrado'
                ]);
            }
        } catch (PDOException $e) {
            return json_encode([
                'status' => false,
                'message' => 'Error de base de datos: ' . $e->getMessage()
            ]);
        }
    }

    public function update($datos, $id)
    {
        try {
            // Prepara la consulta SQL para actualizar una pregunta
            $this->db->query('UPDATE questions SET 
                question = :question, 
                type = :type, 
                section = :section, 
                percentage = :percentage, 
                frm_option = :frm_option, 
                conditional = :conditional, 
                id_conditional = :id_conditional, 
                conditional_answer = :conditional_answer, 
                survey_id = :survey_id 
                WHERE id = :id');
    
            // Asigna valores a los parámetros de la consulta
            $this->db->bind(':id', $id);
            $this->db->bind(':question', $datos['question']);
            $this->db->bind(':type', $datos['type']);
            $this->db->bind(':section', $datos['section']);
            $this->db->bind(':percentage', $datos['percentage']);
            $this->db->bind(':frm_option', $datos['frm_option']);
            $this->db->bind(':conditional', $datos['conditional']);
            $this->db->bind(':id_conditional', $datos['id_conditional']);
            $this->db->bind(':conditional_answer', $datos['conditional_answer']);
            $this->db->bind(':survey_id', $datos['survey_id']);
    
            // Ejecuta la consulta y retorna true si tiene éxito, false si falla
            if ($this->db->execute()) {
                return json_encode([
                    'status' => true,
                    'message' => 'Pregunta actualizada exitosamente'
                ]);
            } else {
                return json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar la pregunta'
                ]);
            }
        } catch (PDOException $e) {
            return json_encode([
                'status' => false,
                'message' => 'Error de base de datos: ' . $e->getMessage()
            ]);
        }
    }
    
}
