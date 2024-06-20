<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase EndUserClientController que extiende de Controlador
class EndUserClientController extends Controlador
{
    // Propiedad para almacenar la instancia del modelo EndUser_Client
    private $EndUserClient;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo EndUser_Client
            $this->EndUserClient = $this->modelo("endUser_client");
        });
    }

    // Método para obtener todos los registros de enduser_clients
    public function EndUsersclients()
    {
        $listar = $this->EndUserClient->list();
        echo json_encode($listar);
    }

    // Método para obtener un registro de enduser_clients por ID
    public function EndUserClientByID($id)
    {
        $listar = $this->EndUserClient->listByID($id);
        echo json_encode($listar);
    }

    // Método para crear nuevas asociaciones entre endUsers y clientes
    public function postEndUserClient()
    {
        $jsonValidationMiddleware = new JsonValidationMiddleware(['idEndUser', 'idClient']);

        $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {
            // Iterar sobre cada elemento del array y realizar la inserción
            $results = [];
            foreach ($data as $datos) {

                // Llama al modelo para realizar la inserción del endUser-cliente
                $result = $this->EndUserClient->create($datos);
                if ($result['status'] === true) {
                    $results[] = [
                        'idEndUser' => $datos['idEndUser'],
                        'idClient' => $datos['idClient'],
                        'status' => true,
                        'message' => 'Asociación cliente-endUser creada exitosamente'
                    ];
                } else {
                    // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                    $results[] = [
                        'idEndUser' => $datos['idEndUser'],
                        'idClient' => $datos['idClient'],
                        'status' => false,
                        'message' => 'Error al crear la asociación cliente-endUser: ' . $result['message']
                    ];
                }
            }

            echo json_encode($results);
        });
    }
    // Método para actualizar una asociación endUser-cliente por ID
    public function putEndUserClient($id)
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
            if (!isset($data['idEndUser']) || !isset($data['idClient'])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            // Asignar los valores del array $data al array $datos
            $datos = [
                'idEndUser' => trim($data['idEndUser']),
                'idClient' => trim($data['idClient']),
            ];

            // Llama al modelo para realizar la actualización del endUser-cliente
            $result = $this->EndUserClient->update($datos, $id);
            if ($result === true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Asociación cliente-endUser actualizada exitosamente'
                ]);
            } else {
                // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar la asociación cliente-endUser: ' . $result
                ]);
            }
        }
    }
}
