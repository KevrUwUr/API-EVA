<?php
// Definición de la clase Cliente
class EndUser
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
        $this->db->query("SELECT * FROM end_users");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listActive()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes activos
        $this->db->query("SELECT * FROM end_users WHERE estado = 1 ");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listInactive()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes inactivos
        $this->db->query("SELECT * FROM end_users WHERE estado = 0 ");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listByID($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT * FROM end_users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function create($datos)
    {
        // Se ejecuta una consulta SQL para insertar un nuevo cliente
        $this->db->query('INSERT INTO end_users(firstname, middlename, lastname, email) 
            VALUES (:firstname, middlename, lastname, email)');

        // Asignar valores a los parámetros
        $this->db->bind(':firstname', $datos['firstname']);
        $this->db->bind(':middlename', $datos['middlename']);
        $this->db->bind(':lastname', $datos['lastname']);
        $this->db->bind(':email', $datos['email']);

        // Ejecutar la consulta y retornar true si tiene éxito, false si falla
        return $this->db->execute();
    }

    public function update($datos, $id)
    {
        // Prepara la consulta SQL para actualizar un cliente
        $this->db->query('UPDATE users SET firstname=:firstname, middlename=:middlename, lastname=:lastname, email=:email, WHERE id = :id');

        // Asigna valores a los parámetros de la consulta
        $this->db->bind(':id', $id);
        $this->db->bind(':firstname', $datos['firstname']);
        $this->db->bind(':middlename', $datos['middlename']);
        $this->db->bind(':lastname', $datos['lastname']);
        $this->db->bind(':email', $datos['email']);

        // Ejecuta la consulta y retorna true si tiene éxito, false si falla
        return $this->db->execute();
    }

    public function patchEstado($id, $estado)
    {
        // Prepara la consulta SQL para actualizar el estado del usuario
        $this->db->query('UPDATE users SET estado = :estado WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $estado);
        return $this->db->execute();
    }
}
