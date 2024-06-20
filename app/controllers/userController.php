<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class UserController extends Controlador
{
    private $User;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo User
            $this->User = $this->modelo("user");
        });
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
        // Usar middleware JsonValidationMiddleware
        $jsonValidationMiddleware = new JsonValidationMiddleware(['lastname', 'firstname', 'middlename', 'email', 'password', 'type', 'language', 'registration_date', 'last_visit_date']);

        // Manejar la validación y procesamiento del usuario
        $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {
            // Procesar creación de usuario
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

            // Intentar crear usuario
            if ($this->User->create($datos)) {
                $idCreation = $this->User->getId();
                echo json_encode([
                    'status' => true,
                    'message' => 'Usuario creado exitosamente',
                    'id' => $idCreation->id
                ]);
            } else {
                echo json_encode(['status' => false, 'message' => 'Error al crear el usuario']);
            }
        });
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

            $datos = [];
            if (isset($data['lastname'])) $datos['lastname'] = trim($data['lastname']);
            if (isset($data['firstname'])) $datos['firstname'] = trim($data['firstname']);
            if (isset($data['middlename'])) $datos['middlename'] = trim($data['middlename']);
            if (isset($data['email'])) $datos['email'] = trim($data['email']);
            if (isset($data['password'])) $datos['password'] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
            if (isset($data['type'])) $datos['type'] = trim($data['type']);
            if (isset($data['language'])) $datos['language'] = trim($data['language']);
            if (isset($data['registration_date'])) $datos['registration_date'] = trim($data['registration_date']);
            if (isset($data['last_visit_date'])) $datos['last_visit_date'] = trim($data['last_visit_date']);

            // Filtrar los campos que no estén vacíos
            $datos = array_filter($datos, function ($value) {
                return $value !== '';
            });

            if (empty($datos)) {
                echo json_encode(['status' => false, 'message' => 'No se proporcionaron campos para actualizar']);
                return;
            }

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
