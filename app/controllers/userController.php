<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class UserController extends Controlador
{
    // Constructor de la clase
    public function __construct()
    {
        // Se instancia el modelo User
        $this->User = $this->modelo("user");
    }

    public function Users()
    {
        $listar = $this->User->list();
        echo json_encode($listar);
    }

    public function UserByID($id)
    {
        $listar = $this->User->listByID($id);
        echo json_encode($listar);
    }

    // Método para insertar un usuario
    public function postUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el cuerpo de la solicitud
            $body = file_get_contents('php://input');

            // Decodificar el JSON recibido en un array asociativo
            $data = json_decode($body, true);

            // Verificar si json_decode tuvo éxito
            if (is_null($data)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al decodificar JSON'
                ]);
                return;
            }

            // Verificar si las claves existen en el array
            if (!isset($data['user']) || !isset($data['logo']) || !isset($data['estado'])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            $datos = [
                'lastname' => trim($data['user']),
                'firstname' => '',
                'middlename' => '',
                'email' => '',
                'password' => '',
                'type' => '',
                'language' => '',
                'registration_date' => '',
                'last_visit_date' => '',
            ];

            // Llama al modelo para realizar la inserción del usuario
            if ($this->User->create($datos)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Usuario creado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al crear el usuario'
                ]);
            }
        }
    }

    public function putUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Leer el cuerpo de la solicitud
            $body = file_get_contents('php://input');

            // Decodificar el JSON recibido en un array asociativo
            $data = json_decode($body, true);

            // Verificar si json_decode tuvo éxito
            if (is_null($data)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al decodificar JSON'
                ]);
                return;
            }

            if (!isset($data['user']) || !isset($data['logo']) || !isset($data['estado'])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            $datos = [
                'id' => $id,
                'lastname' => trim($data['user']),
                'firstname' => '',
                'middlename' => '',
                'email' => '',
                'password' => '',
                'type' => '',
                'language' => '',
                'registration_date' => '',
                'last_visit_date' => '',
            ];

            // Llama al modelo para realizar la actualización del usuario
            if ($this->User->update($datos, $id)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Usuario actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar el usuario'
                ]);
            }
        }
    }
}
