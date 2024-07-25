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
    $this->db->query("SELECT * FROM questions WHERE id = :id");
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
          return [
            'status' => true,
            'message' => 'Pregunta creada exitosamente'
          ];
        } else {
          return [
            'status' => false,
            'message' => 'Error al insertar los datos'
          ];
        }
      } else {
        // survey_id no existe, retornar mensaje de error
        return [
          'status' => false,
          'message' => 'survey_id no encontrado'
        ];
      }
    } catch (PDOException $e) {
      // Manejar errores de base de datos
      return [
        'status' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
      ];
    }
  }


  public function update($datos, $id)
  {
    try {
      // Comienza la consulta SQL
      $query = 'UPDATE questions SET ';

      // Inicializa un array para los fragmentos de la consulta y los valores
      $setFragments = [];
      $values = [];

      // Recorre los datos y construye los fragmentos de la consulta y los valores
      foreach ($datos as $key => $value) {
        $setFragments[] = "{$key} = :{$key}";
        $values[":{$key}"] = $value;
      }

      // Combina los fragmentos en la consulta
      $query .= implode(', ', $setFragments);
      $query .= ' WHERE id = :id';

      // Prepara la consulta
      $this->db->query($query);

      // Asigna los valores a los parámetros de la consulta
      foreach ($values as $placeholder => $val) {
        $this->db->bind($placeholder, $val);
      }
      $this->db->bind(':id', $id);

      // Ejecuta la consulta y retorna el resultado
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


  public function delete($id)
  {
    try {
      // Verificar si la pregunta existe antes de intentar eliminarla
      $question = $this->listByID($id); // Método que deberías tener en tu modelo para buscar por ID

      if (!$question) {
        return [
          'status' => false,
          'message' => 'La pregunta con ID ' . $id . ' no existe'
        ];
      }

      // Prepara la consulta SQL para eliminar una pregunta
      $this->db->query('DELETE FROM questions WHERE id = :id');

      // Asigna valores a los parámetros de la consulta
      $this->db->bind(':id', $id);

      // Ejecuta la consulta
      if ($this->db->execute()) {
        return [
          'status' => true,
          'message' => 'Pregunta eliminada exitosamente'
        ];
      } else {
        return [
          'status' => false,
          'message' => 'Error al eliminar la pregunta'
        ];
      }
    } catch (PDOException $e) {
      return [
        'status' => false,
        'message' => 'Error de base de datos: ' . $e->getMessage()
      ];
    }
  }
}
