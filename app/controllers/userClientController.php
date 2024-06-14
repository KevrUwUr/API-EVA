<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase UserClientController que extiende de Controlador
class UserClientController extends Controlador
{
    // Propiedad para almacenar la instancia del modelo User_Client
    private $UserClient;

    // Constructor de la clase
    public function __construct()
    {
        // Se instancia el modelo User_Client
        $this->UserClient = $this->modelo("user_client");
    }

    // Método para obtener todos los registros de user_clients
    public function Usersclients()
    {
        $listar = $this->UserClient->list();
        echo json_encode($listar);
    }

    // Método para obtener un registro de user_clients por ID
    public function UserClientByID($id)
    {
        $listar = $this->UserClient->listByID($id);
        echo json_encode($listar);
    }

    public function postUserClient()
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

            // Iterar sobre cada elemento del array y realizar la inserción
            $results = [];
            foreach ($data as $datos) {
                // Verificar si los datos requeridos están presentes en la solicitud
                if (!isset($datos['idUser']) || !isset($datos['idClient'])) {
                    $results[] = [
                        'idUser' => $datos['idUser'] ?? null,
                        'idClient' => $datos['idClient'] ?? null,
                        'status' => false,
                        'message' => 'Datos incompletos en la solicitud'
                    ];
                    continue;
                }

                // Llama al modelo para realizar la inserción del usuario-cliente
                $result = $this->UserClient->create($datos);
                if ($result === true) {
                    $results[] = [
                        'idUser' => $datos['idUser'],
                        'idClient' => $datos['idClient'],
                        'status' => true,
                        'message' => 'Asociación cliente-usuario creada exitosamente'
                    ];
                } else {
                    // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                    $results[] = [
                        'idUser' => $datos['idUser'],
                        'idClient' => $datos['idClient'],
                        'status' => false,
                        'message' => 'Error al crear la asociación cliente-usuario: ' . $result
                    ];
                }
            }

            // Retornar los resultados como un array de JSON
            echo json_encode($results);
        }
    }

    // Método para actualizar una asociación usuario-cliente por ID
    public function putUserClient($id)
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

            // Verificar si los datos requeridos están presentes en la solicitud
            if (!isset($data['idUser']) || !isset($data['idClient'])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            // Asignar los valores del array $data al array $datos
            $datos = [
                'idUser' => trim($data['idUser']),
                'idClient' => trim($data['idClient']),
            ];

            // Llama al modelo para realizar la actualización del usuario-cliente
            $result = $this->UserClient->update($datos, $id);
            if ($result === true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Asociación cliente-usuario actualizada exitosamente'
                ]);
            } else {
                // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar la asociación cliente-usuario: ' . $result
                ]);
            }
        }
    }
}
