<?php
// Definición de la clase Cliente
class User
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
        $this->db->query("SELECT * FROM users");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listByID($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }
    public function create($datos)
    {
        // Se ejecuta una consulta SQL para insertar un nuevo cliente
        $this->db->query('INSERT INTO users (lastname, firstname, middlename, email, password, type, language, registration_date, last_visit_date) 
            VALUES (:lastname, :firstname, :middlename, :email, :password, :type, :language, :registration_date, :last_visit_date)');

        // Asignar valores a los parámetros
        $this->db->bind(':lastname', $datos['lastname']);
        $this->db->bind(':firstname', $datos['firstname']);
        $this->db->bind(':middlename', $datos['middlename']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':password', $datos['password']);
        $this->db->bind(':type', $datos['type']);
        $this->db->bind(':language', $datos['language']);
        $this->db->bind(':registration_date', $datos['registration_date']);
        $this->db->bind(':last_visit_date', $datos['last_visit_date']);

        // Ejecutar la consulta y retornar true si tiene éxito, false si falla
        return $this->db->execute();
    }


    public function update($datos, $id)
    {
        // Prepara la consulta SQL para actualizar un cliente
        $this->db->query('UPDATE users SET id=:id, lastname=:lastname, firstname=:firstname, middlename=:middlename, email=:email, password=:password, type=:type, language=:language, registration_date=:registration_date, last_visit_date=:last_visit_date WHERE id = :id');

        // Asigna valores a los parámetros de la consulta
        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':lastname', $datos['lastname']);
        $this->db->bind(':firstname', $datos['firstname']);
        $this->db->bind(':middlename', $datos['middlename']);
        $this->db->bind(':email', $datos['email']);
        $this->db->bind(':password', $datos['password']);
        $this->db->bind(':type', $datos['type']);
        $this->db->bind(':language', $datos['language']);
        $this->db->bind(':registration_date', $datos['registration_date']);
        $this->db->bind(':last_visit_date', $datos['last_visit_date']);

        // Ejecuta la consulta y retorna true si tiene éxito, false si falla
        return $this->db->execute();
    }
}
