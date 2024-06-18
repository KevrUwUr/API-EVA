<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class UserController extends Controlador
{
    private $User;
    // Constructor de la clase
    public function __construct()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization']) || !Base::tokenValidate(str_replace('Bearer ', '', $headers['Authorization']))) {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'Token no válido o expirado']);
            exit;
        }
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

    public function postUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (is_null($data)) {
                echo json_encode(['status' => false, 'message' => 'Error al decodificar JSON']);
                return;
            }

            // Verificación de campos requeridos
            $requiredFields = ['lastname', 'firstname', 'middlename', 'email', 'password', 'type', 'language', 'registration_date', 'last_visit_date'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    echo json_encode(['status' => false, 'message' => "El campo $field es obligatorio"]);
                    return;
                }
            }

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

            if ($this->User->create($datos)) {
                $idCreation = $this->User->getId(); // Obtener el ID del usuario recién creado
                echo json_encode([
                    'status' => true,
                    'message' => 'Usuario creado exitosamente',
                    'id' => $idCreation->id
                ]);
            } else {
                echo json_encode(['status' => false, 'message' => 'Error al crear el usuario']);
            }
        }
    }



    public function putUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (is_null($data)) {
                echo json_encode(['status' => false, 'message' => 'Error al decodificar JSON']);
                return;
            }

            $requiredFields = ['lastname', 'firstname', 'middlename', 'email', 'type', 'language', 'registration_date', 'last_visit_date'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    echo json_encode(['status' => false, 'message' => "El campo $field es obligatorio"]);
                    return;
                }
            }

            $datos = [
                'lastname' => trim($data['lastname']),
                'firstname' => trim($data['firstname']),
                'middlename' => trim($data['middlename']),
                'email' => trim($data['email']),
                'password' => isset($data['password']) ? password_hash(trim($data['password']), PASSWORD_DEFAULT) : '',
                'type' => trim($data['type']),
                'language' => trim($data['language']),
                'registration_date' => trim($data['registration_date']),
                'last_visit_date' => trim($data['last_visit_date']),
            ];

            $datos = array_filter($datos, function ($value) {
                return $value !== '';
            });

            if ($this->User->update($datos, $id)) {
                echo json_encode(['status' => true, 'message' => 'Usuario actualizado exitosamente']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Error al actualizar el usuario']);
            }
        }
    }


    public function patchUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (is_null($data) || !isset($data['state']) || !in_array($data['state'], [0, 1])) {
                echo json_encode(['status' => false, 'message' => 'Datos incorrectos en la solicitud']);
                return;
            }

            $state = $data['state'];

            if ($this->User->patchstate($id, $state)) {
                echo json_encode(['status' => true, 'message' => 'Estado del usuario actualizado exitosamente']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Error al actualizar el estado del usuario']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Método no permitido']);
        }
    }
}
