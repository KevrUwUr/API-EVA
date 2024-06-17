<?php
// Definición de la clase User_Client
class User_Client
{
    // Propiedad privada para la conexión a la base de datos
    private $db;

    // Constructor de la clase
    public function __construct()
    {
        // Se instancia la clase Base para la conexión a la base de datos
        $this->db = new Base;
    }

    // Método para listar todos los registros de user_clients
    public function list()
    {
        $this->db->query("SELECT * FROM user_clients");
        return $this->db->registros();
    }

    // Método para obtener un registro de user_clients por ID
    public function listByID($id)
    {
        $this->db->query("SELECT * FROM user_clients WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Método para crear una nueva asociación entre usuario y cliente
    public function create($datos)
    {
        try {
            // Verificar si el idClient y el idUser existen
            $this->db->query('SELECT COUNT(*) AS count FROM clients WHERE id = :idClient');
            $this->db->bind(':idClient', $datos['idClient']);
            $resultClient = $this->db->single();

            $this->db->query('SELECT COUNT(*) AS count FROM users WHERE id = :idUser');
            $this->db->bind(':idUser', $datos['idUser']);
            $resultUser = $this->db->single();

            // Verificar si ambos existen
            if ($resultClient['count'] > 0 && $resultUser['count'] > 0) {
                // idClient e idUser existen, proceder con la inserción
                $this->db->query('INSERT INTO user_clients (idUser, idClient) VALUES (:idUser, :idClient)');
                $this->db->bind(':idUser', $datos['idUser']);
                $this->db->bind(':idClient', $datos['idClient']);

                // Ejecutar la consulta y retornar true si tiene éxito
                if ($this->db->execute()) {
                    return true;
                } else {
                    return 'Error al insertar la asociación usuario-cliente';
                }
            } else {
                // idClient o idUser no existe, retornar mensaje de error
                return 'idClient o idUser no encontrado';
            }
        } catch (PDOException $e) {
            // Capturar excepciones de base de datos
            return 'Error de base de datos: ' . $e->getMessage();
        }
    }

    // Método para actualizar una asociación entre usuario y cliente
    public function update($datos, $id)
    {
        try {
            // Preparar la consulta SQL para actualizar la asociación usuario-cliente
            $this->db->query('UPDATE user_clients SET idUser=:idUser, idClient=:idClient WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':idUser', $datos['idUser']);
            $this->db->bind(':idClient', $datos['idClient']);

            // Ejecutar la consulta y retornar true si tiene éxito
            if ($this->db->execute()) {
                return true;
            } else {
                return 'Error al actualizar la asociación usuario-cliente';
            }
        } catch (PDOException $e) {
            // Capturar excepciones de base de datos
            return 'Error de base de datos: ' . $e->getMessage();
        }
    }
}
?>
