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

    public function listActive()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes activos
        $this->db->query("SELECT * FROM users WHERE state = 1 ");
        // Retorna el resultado como un array asociativo
        return $this->db->registros();
    }

    public function listInactive()
    {
        // Se ejecuta una consulta SQL para obtener la información de los clientes inactivos
        $this->db->query("SELECT * FROM users WHERE state = 0 ");
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

    public function listClientsxUser($id)
    {
        // Se ejecuta una consulta SQL para obtener la información del cliente por ID
        $this->db->query("SELECT c.id, c.client FROM users u INNER JOIN user_clients uc ON u.id = uc.idUser INNER JOIN clients c ON c.id = uc.idClient WHERE u.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    public function getId()
    {
        // Se ejecuta una consulta SQL para obtener el último ID insertado en la tabla users
        $this->db->query("SELECT id, type FROM users ORDER BY id DESC LIMIT 1");
        return $this->db->registro();
    }

    public function create($datos)
    {
        // Se ejecuta una consulta SQL para insertar un nuevo cliente
        $this->db->query('INSERT INTO users (lastname, firstname, middlename, email, password, type, language, registration_date, last_visit_date, state) 
            VALUES (:lastname, :firstname, :middlename, :email, :password, :type, :language, :registration_date, :last_visit_date, 1)');

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
        // Iniciar la consulta SQL
        $sql = 'UPDATE users SET ';

        // Construir los fragmentos de la consulta
        $setClause = [];
        foreach ($datos as $key => $value) {
            $setClause[] = "$key = :$key";
        }
        $sql .= implode(', ', $setClause);
        $sql .= ' WHERE id = :id';

        // Preparar la consulta SQL
        $this->db->query($sql);

        // Asignar valores a los parámetros de la consulta
        foreach ($datos as $key => $value) {
            $this->db->bind(":$key", $value);
        }
        $this->db->bind(':id', $id);

        // Ejecutar la consulta y retornar true si tiene éxito, false si falla
        return $this->db->execute();
    }


    public function patchstate($id, $state)
    {
        // Prepara la consulta SQL para actualizar el state del usuario
        $this->db->query('UPDATE users SET state = :state WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':state', $state);
        return $this->db->execute();
    }
}
