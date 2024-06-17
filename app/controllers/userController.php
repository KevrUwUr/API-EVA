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

    public function UsersActive()
    {
        $listar = $this->User->listActive();
        echo json_encode($listar);
    }

    public function UsersInactive()
    {
        $listar = $this->User->listInactive();
        echo json_encode($listar);
    }

    public function UserByID($id)
    {
        $listar = $this->User->listByID($id);
        echo json_encode($listar);
    }

    public function ClientsxUser($id)
    {
        $listar = $this->User->listClientsxUser($id);
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

            if (
                !isset($data['lastname'])
                || !isset($data['firstname'])
                || !isset($data['middlename'])
                || !isset($data['email'])
                || !isset($data['password'])
                || !isset($data['type'])
                || !isset($data['language'])
                || !isset($data['registration_date'])
                || !isset($data['last_visit_date'])
            ) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            // Asignar los valores del array $data al array $datos
            $datos = [
                'lastname' => trim($data['lastname']),
                'firstname' => trim($data['firstname']),
                'middlename' => trim($data['middlename']),
                'email' => trim($data['email']),
                'password' => password_hash(trim($data['password']), PASSWORD_DEFAULT),
                'type' => trim($data['type']),
                'language' => trim($data['language']),
                'registration_date' => trim($data['registration_date']),
                'last_visit_date' => trim($data['last_visit_date']),
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

            // Verificar si las claves existen en el array
            if (
                !isset($data['lastname'])
                || !isset($data['firstname'])
                || !isset($data['middlename'])
                || !isset($data['email'])
                || !isset($data['password'])
                || !isset($data['type'])
                || !isset($data['language'])
                || !isset($data['registration_date'])
                || !isset($data['last_visit_date'])
            ) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            // Asignar los valores del array $data al array $datos
            $datos = [
                'lastname' => trim($data['lastname']),
                'firstname' => trim($data['firstname']),
                'middlename' => trim($data['middlename']),
                'email' => trim($data['email']),
                // Si la contraseña es opcional en la actualización, debes verificar si se incluye y hashearla
                'password' => isset($data['password']) ? password_hash(trim($data['password']), PASSWORD_DEFAULT) : '',
                'type' => trim($data['type']),
                'language' => trim($data['language']),
                'registration_date' => trim($data['registration_date']),
                'last_visit_date' => trim($data['last_visit_date']),
            ];

            // Remover campos vacíos para no actualizar con datos vacíos
            $datos = array_filter($datos, function ($value) {
                return $value !== '';
            });

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

    public function patchUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (is_null($data) || !isset($data['estado']) || !in_array($data['estado'], [0, 1])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incorrectos en la solicitud'
                ]);
                return;
            }

            $estado = $data['estado'];

            if ($this->User->patchEstado($id, $estado)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Estado del usuario actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar el estado del usuario'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Método no permitido'
            ]);
        }
    }
}
