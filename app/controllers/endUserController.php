<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class EndUserController extends Controlador
{
    private $EndUser;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo EndUser
            $this->EndUser = $this->modelo("endUser");
        });
    }

    public function EndUsers()
    {
        $listar = $this->EndUser->list();
        echo json_encode($listar);
    }

    public function EndUsersActive()
    {
        $listar = $this->EndUser->listActive();
        echo json_encode($listar);
    }

    public function EndUsersInactive()
    {
        $listar = $this->EndUser->listInactive();
        echo json_encode($listar);
    }

    public function EndUserByID($id)
    {
        $listar = $this->EndUser->listByID($id);
        echo json_encode($listar);
    }

    // Método para insertar un usuario
    public function postEndUser()
    {
        // Usar middleware JsonValidationMiddleware
        $jsonValidationMiddleware = new JsonValidationMiddleware(['lastname', 'firstname', 'middlename', 'email']);

        // Manejar la validación y procesamiento del usuario
        $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {

            // Asignar los valores del array $data al array $datos
            $datos = [
                'lastname' => trim($data['lastname']),
                'firstname' => trim($data['firstname']),
                'middlename' => trim($data['middlename']),
                'email' => trim($data['email']),
            ];


            // Llama al modelo para realizar la inserción del usuario
            $result = $this->EndUser->create($datos);
            if ($result == true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'EndUser creado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al crear endUser'. json_encode($result)
                ]);
            }
        });
    }

    public function putEndUser($id)
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
            ];

            // Remover campos vacíos para no actualizar con datos vacíos
            $datos = array_filter($datos, function ($value) {
                return $value !== '';
            });

            // Llama al modelo para realizar la actualización del usuario
            if ($this->EndUser->update($datos, $id)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'EndUser actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar EndUser'
                ]);
            }
        }
    }

    public function patchEndUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (is_null($data) || !isset($data['state']) || !in_array($data['state'], [0, 1])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incorrectos en la solicitud'
                ]);
                return;
            }

            $state = $data['state'];

            if ($this->EndUser->patchstate($id, $state)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'state del endUser actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar el state del endUser'
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
