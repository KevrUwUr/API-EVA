<?php
// Definición de la clase Cliente
class Cliente
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
        $this->db->query("SELECT * FROM clients");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listActive()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes
        $this->db->query("SELECT * FROM clients WHERE state = 1");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }
    
    public function listInactive()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes
        $this->db->query("SELECT * FROM clients WHERE state = 0");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listByID($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT * FROM clients WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function create($datos)
    {
        // Se ejecuta una consulta SQL para insertar un nuevo cliente
        $this->db->query('INSERT INTO clients(client, logo, state) VALUES (:cliente, :logo, 1)');
        // Asignar valores a los parámetros
        $this->db->bind(':cliente', $datos['client']);
        $this->db->bind(':logo', $datos['logo']);
        // Ejecutar la consulta y retornar true si tiene éxito, false si falla
        return $this->db->execute();
    }

    public function update($datos, $id)
    {
        // Prepara la consulta SQL para actualizar un cliente
        $this->db->query('UPDATE clients SET client = :cliente, logo = :logo, state = 1 WHERE id = :id');

        // Asigna valores a los parámetros de la consulta
        $this->db->bind(':cliente', $datos['client']);
        $this->db->bind(':logo', $datos['logo']);
        $this->db->bind(':state', $datos['state']);

        // Ejecuta la consulta y retorna true si tiene éxito, false si falla
        return $this->db->execute();
    }

    public function patchstate($id, $state)
    {
        // Prepara la consulta SQL para actualizar el state del usuario
        $this->db->query('UPDATE clients SET state = :state WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':state', $state);
        return $this->db->execute();
    }
}
