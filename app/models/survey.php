<?php
// Definición de la clase Cliente
class Survey
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
        $this->db->query("SELECT * FROM survey_set");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listByID($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT * FROM survey_set WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function create($datos)
    {
        try {
            // Verificar si el idClient existe
            $this->db->query('SELECT COUNT(*) AS count FROM clients WHERE id = :idClient');
            $this->db->bind(':idClient', $datos['idClient']);
            $result = $this->db->single();
            if ($result['count'] > 0) {
                // idClient existe, proceder con la inserción
                $this->db->query('INSERT INTO survey_set (title, start_date, end_date, description, link, type, idClient)
                    VALUES (:title, :start_date, :end_date, :description, :link, :type, :idClient)');

                // Asignar valores a los parámetros
                $this->db->bind(':title', $datos['title']);
                $this->db->bind(':start_date', $datos['start_date']);
                $this->db->bind(':end_date', $datos['end_date']);
                $this->db->bind(':description', $datos['description']);
                $this->db->bind(':link', $datos['link']);
                $this->db->bind(':type', $datos['type']);
                $this->db->bind(':idClient', $datos['idClient']);

                // Ejecutar la consulta y retornar true si tiene éxito
                if ($this->db->execute()) {
                    return json_encode([
                        'status' => true,
                        'message' => 'Encuesta creada exitosamente'
                    ]);
                } else {
                    return json_encode([
                        'status' => false,
                        'message' => 'Error al insertar los datos'
                    ]);
                }
            } else {
                // idClient no existe, retornar mensaje de error
                return 'idClient no encontrado';
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
        // Prepara la consulta SQL para actualizar un cliente
        $this->db->query('UPDATE survey_set SET title=:title, start_date=:start_date, end_date=:end_date, description=:description, link=:link, type=:type, idClient=:idClient WHERE id = :id');

        // Asigna valores a los parámetros de la consulta
        $this->db->bind(':id', $id);
        $this->db->bind(':title', $datos['title']);
        $this->db->bind(':start_date', $datos['start_date']);
        $this->db->bind(':end_date', $datos['end_date']);
        $this->db->bind(':description', $datos['description']);
        $this->db->bind(':link', $datos['link']);
        $this->db->bind(':type', $datos['type']);
        $this->db->bind(':idClient', $datos['idClient']);

        // Ejecuta la consulta y retorna true si tiene éxito, false si falla
        return $this->db->execute();
    }
}
