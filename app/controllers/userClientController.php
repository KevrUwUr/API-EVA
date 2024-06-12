<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class UserClientController extends Controlador
{
    // Constructor de la clase
    public function __construct()
    {
        // Se instancia el modelo UserClient
        $this->UserClient = $this->modelo("user_client");
    }

    public function Usersclients()
    {
        $listar = $this->UserClient->list();
        echo json_encode($listar);
    }

    public function UserClientByID($id)
    {
        $listar = $this->UserClient->listByID($id);
        echo json_encode($listar);
    }

    // Método para insertar un usuario
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

            if (
                !isset($data['idUser'])
                || !isset($data['idClient'])
            ) {
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

            // Llama al modelo para realizar la inserción del usuario
            $result = $this->UserClient->create($datos);
            if ($result === true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Encuesta creada exitosamente'
                ]);
            } else {
                // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al crear la encuesta: ' . $result
                ]);
            }
        }
    }

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

            // Verificar si las claves existen en el array
            if (
                !isset($data['idUser'])
                || !isset($data['idClient'])
                || !isset($data['end_date'])
                || !isset($data['description'])
                || !isset($data['link'])
                || !isset($data['type'])
                || !isset($data['idClient'])
            ) {
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
                'end_date' => trim($data['end_date']),
                'description' => trim($data['description']),
                // Si la contraseña es opcional en la actualización, debes verificar si se incluye y hashearla
                'link' =>  trim($data['description']),
                'type' => trim($data['type']),
                'idClient' => trim($data['idClient']),
            ];

            // Remover campos vacíos para no actualizar con datos vacíos
            $datos = array_filter($datos, function ($value) {
                return $value !== '';
            });

            // Llama al modelo para realizar la actualización del usuario
            if ($this->UserClient->update($datos, $id)) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Encuesta actualizado exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar la encuesta'
                ]);
            }
        }
    }
}