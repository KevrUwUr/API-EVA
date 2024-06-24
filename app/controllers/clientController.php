<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class clientController extends Controlador
{
    private $Cliente;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo cliente
            $this->Cliente = $this->modelo("cliente");
        });
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

    public function postClient()
    {
        // Verificar si el método de la solicitud es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar que se han recibido datos del formulario

            $logo = $_FILES['logo'];

            if (isset($_POST['cliente']) && isset($_FILES['logo'])) {
                $cliente = $_POST['cliente'];
                $logo = $_FILES['logo'];

                // Directorio donde se guardará el archivo de imagen
                $uploadDirectory = 'C:/xampp/htdocs/EVA-React/public/clientes/';
                $uploadPath = $uploadDirectory . basename($logo['name']);

                // Intentar mover el archivo cargado al directorio de destino
                if (move_uploaded_file($logo['tmp_name'], $uploadPath)) {
                    // Preparar datos para guardar en la base de datos
                    $datos = [
                        'client' => trim($cliente),
                        'logo' => basename($logo['name']),
                    ];

                    // Llama al modelo para realizar la inserción del cliente
                    if ($this->Cliente->create($datos)) {
                        echo json_encode([
                            'status' => true,
                            'message' => 'Cliente creado exitosamente',
                            'name' => basename($logo['name'])
                        ]);
                    } else {
                        echo json_encode([
                            'status' => false,
                            'message' => 'Error al crear el cliente'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => 'Error al subir la imagen'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Método no permitido'
            ]);
        }
    }

    public function putClient($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar que se han recibido datos del formulario
            if (isset($_POST['cliente']) && isset($_FILES['logo'])) {
                $cliente = $_POST['cliente'];
                $logo = $_FILES['logo'];

                // Directorio donde se guardará el archivo de imagen
                $uploadDirectory = 'C:/xampp/htdocs/EVA-React/public/clientes/';
                $uploadPath = $uploadDirectory . basename($logo['name']);

                // Intentar mover el archivo cargado al directorio de destino
                if (move_uploaded_file($logo['tmp_name'], $uploadPath)) {
                    // Preparar datos para guardar en la base de datos
                    $datos = [
                        'cliente' => trim($cliente),
                        'logo' => basename($logo['name']),
                        'id' => $id
                    ];

                    // Llama al modelo para realizar la actualización del cliente
                    if ($this->Cliente->update($datos)) {
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
                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => 'Error al subir la imagen'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Método no permitido'
            ]);
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
