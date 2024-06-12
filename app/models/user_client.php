<?php
// Definición de la clase Cliente
class User_Client
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
        $this->db->query("SELECT * FROM user_clients");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listByID($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT * FROM user_clients WHERE id = :id");
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
                $this->db->query('INSERT INTO user_clients(idUser, idClient) VALUES (:idUser,:idClient)');

                // Asignar valores a los parámetros
                $this->db->bind(':idUser', $datos['idUser']);
                $this->db->bind(':isClient', $datos['idClient']);

                // Ejecutar la consulta y retornar true si tiene éxito
                if ($this->db->execute()) {
                    return json_encode([
                        'status' => true,
                        'message' => 'El cliente se asocio al usuario exitosamente'
                    ]);
                } else {
                    return json_encode([
                        'status' => false,
                        'message' => 'El cliente no se asocio al usuario'
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
