<?php
class User_Client
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function list()
    {
        $this->db->query("SELECT * FROM user_clients");
        return $this->db->registros();
    }

    public function listByID($id)
    {
        $this->db->query("SELECT * FROM user_clients WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function create($datos)
    {
        try {
            $this->db->query('SELECT COUNT(*) AS count FROM clients WHERE id = :idClient');
            $this->db->bind(':idClient', $datos['idClient']);
            $resultClient = $this->db->single();

            $this->db->query('SELECT COUNT(*) AS count FROM users WHERE id = :idUser');
            $this->db->bind(':idUser', $datos['idUser']);
            $resultUser = $this->db->single();

            if ($resultClient['count'] > 0 && $resultUser['count'] > 0) {
                $this->db->query('INSERT INTO user_clients (idUser, idClient) VALUES (:idUser, :idClient)');
                $this->db->bind(':idUser', $datos['idUser']);
                $this->db->bind(':idClient', $datos['idClient']);

                if ($this->db->execute()) {
                    return [
                        'status' => true,
                        'message' => 'Asociación cliente-usuario creada exitosamente'
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => 'Error al insertar la asociación usuario-cliente'
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'idClient o idUser no encontrado'
                ];
            }
        } catch (PDOException $e) {
            return [
                'status' => false,
                'message' => 'Error de base de datos: ' . $e->getMessage()
            ];
        }
    }

    public function update($datos, $id)
    {
        try {
            $this->db->query('UPDATE user_clients SET idUser=:idUser, idClient=:idClient WHERE id = :id');
            $this->db->bind(':id', $id);
            $this->db->bind(':idUser', $datos['idUser']);
            $this->db->bind(':idClient', $datos['idClient']);

            if ($this->db->execute()) {
                return [
                    'status' => true,
                    'message' => 'Asociación cliente-usuario actualizada exitosamente'
                ];
            } else {
                return [
                    'status' => false,
                    'message' => 'Error al actualizar la asociación usuario-cliente'
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
