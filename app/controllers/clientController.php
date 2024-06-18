<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class clientController extends Controlador
{
    private $Cliente;

    // Constructor de la clase
    public function __construct()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization']) || !Base::tokenValidate(str_replace('Bearer ', '', $headers['Authorization']))) {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'Token no válido o expirado']);
            exit;
        }
        // Se instancia el modelo cliente
        $this->Cliente = $this->modelo("cliente");
    }

    public function Clients()
    {
        $listar = $this->Cliente->list();
        echo json_encode($listar);
    }

    public function ClientsActive()
    {
        $listar = $this->Cliente->listActive();
        echo json_encode($listar);
    }
    
    public function ClientsInactive()
    {
        $listar = $this->Cliente->listInactive();
        echo json_encode($listar);
    }

    public function ClientByID($id)
    {
        $listar = $this->Cliente->listByID($id);
        echo json_encode($listar);
    }

    // Método para insertar un cliente
    public function postClient()
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
            if (!isset($data['cliente']) || !isset($data['logo'])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            $datos = [
                'client' => trim($data['cliente']),
                'logo' => trim($data['logo']),
                'state' => trim($data['state']),
            ];

            // echo json_encode(['Datos de la peticion' => $datos]);

            // Llama al modelo para realizar la inserción del cliente
            if ($this->Cliente->create($datos)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Cliente creado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al crear el cliente'
                ]);
            }
        }
    }

    public function putClient($id)
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

            if (!isset($data['cliente']) || !isset($data['logo'])) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            $datos = [
                'id' => $id,
                'client' => trim($data['cliente']),
                'logo' => trim($data['logo']),
                'state' => trim($data['state']),
            ];

            // Llama al modelo para realizar la actualización del cliente
            if ($this->Cliente->update($datos, $id)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Cliente actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar el cliente'
                ]);
            }
        }
    }

    public function patchClient($id)
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

            if ($this->Cliente->patchstate($id, $state)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'state de la encuesta actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar el state de la encuesta'
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
