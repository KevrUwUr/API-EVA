<?php
// Definición de la clase EndUser_Client
class EndUser_Client
{
    // Propiedad privada para la conexión a la base de datos
    private $db;

    // Constructor de la clase
    public function __construct()
    {
        // Se instancia la clase Base para la conexión a la base de datos
        $this->db = new Base;
    }

    // Método para listar todos los registros de enduser_clients
    public function list()
    {
        $this->db->query("SELECT * FROM enduser_clients");
        return $this->db->registros();
    }

    // Método para obtener un registro de enduser_clients por ID
    public function listByID($id)
    {
        $this->db->query("SELECT * FROM enduser_clients WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Método para crear una nueva asociación entre usuario y cliente
    public function create($datos)
    {
        try {
            // Verificar si el idClient y el idEndUser existen
            $this->db->query('SELECT COUNT(*) AS count FROM clients WHERE id = :idClient');
            $this->db->bind(':idClient', $datos['idClient']);
            $resultClient = $this->db->single();

            $this->db->query('SELECT COUNT(*) AS count FROM end_users WHERE id = :idEndUser');
            $this->db->bind(':idEndUser', $datos['idEndUser']);
            $resultUser = $this->db->single();

            // Verificar si ambos existen
            if ($resultClient['count'] > 0 && $resultUser['count'] > 0) {
                // idClient e idEndUser existen, proceder con la inserción
                $this->db->query('INSERT INTO enduser_clients (idEndUser, idClient) VALUES (:idEndUser, :idClient)');
                $this->db->bind(':idEndUser', $datos['idEndUser']);
                $this->db->bind(':idClient', $datos['idClient']);

                // Ejecutar la consulta y retornar true si tiene éxito
                if ($this->db->execute()) {
                    return true;
                } else {
                    return 'Error al insertar la asociación endUser-cliente';
                }
            } else {
                // idClient o idEndUser no existe, retornar mensaje de error
                return 'idClient o idEndUser no encontrado';
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
            $this->db->query('UPDATE enduser_clients SET idEndUser=:idEndUser, idClient=:idClient WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':idEndUser', $datos['idEndUser']);
            $this->db->bind(':idClient', $datos['idClient']);

            // Ejecutar la consulta y retornar true si tiene éxito
            if ($this->db->execute()) {
                return true;
            } else {
                return 'Error al actualizar la asociación endUser-cliente';
            }
        } catch (PDOException $e) {
            // Capturar excepciones de base de datos
            return 'Error de base de datos: ' . $e->getMessage();
        }
    }
}
?>
