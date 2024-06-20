<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class SurveyController extends Controlador
{
    private $Survey;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo Survey
            $this->Survey = $this->modelo("survey");
        });
    }

    public function Surveys()
    {
        $listar = $this->Survey->list();
        echo json_encode($listar);
    }

    public function SurveysActive()
    {
        $listar = $this->Survey->listActive();
        echo json_encode($listar);
    }

    public function SurveysInactive()
    {
        $listar = $this->Survey->listInactive();
        echo json_encode($listar);
    }

    public function SurveyByID($id)
    {
        $listar = $this->Survey->listByID($id);
        echo json_encode($listar);
    }

    public function QuestionxSurvey($id)
    {
        // Llamar al método del modelo para obtener las encuestas de la encuesta
        $questions = $this->Survey->listQuestionsxSurvey($id);

        // Verificar si se produjo un error en la consulta
        if (isset($questions['error'])) {
            echo json_encode([
                'status' => false,
                'message' => $questions['message']
            ]);
        } else {
            // Convertir el resultado en formato JSON y enviarlo como respuesta
            echo json_encode([
                'status' => true,
                'data' => $questions  // Aquí se asume que $questions ya es un array de resultados
            ]);
        }
    }



    // Método para insertar un usuario
    public function postSurvey()
    {
        // Usar middleware JsonValidationMiddleware
        $jsonValidationMiddleware = new JsonValidationMiddleware(['title', 'start_date', 'end_date', 'description', 'link', 'type', 'idClient']);

        // Manejar la validación y procesamiento del usuario
        $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {

            // Asignar los valores del array $data al array $datos
            $datos = [
                'title' => trim($data['title']),
                'start_date' => trim($data['start_date']),
                'end_date' => trim($data['end_date']),
                'description' => trim($data['description']),
                'link' => trim($data['link']),
                'type' => trim($data['type']),
                'idClient' => trim($data['idClient']),
            ];

            // Llama al modelo para realizar la inserción del usuario
            $result = $this->Survey->create($datos);
            if ($result == true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Encuesta creada exitosamente'
                ]);
            } else {
                // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al crear la encuesta: ' . json_encode($result)
                ]);
            }
        });
    }

    public function putSurvey($id)
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
                !isset($data['title'])
                || !isset($data['start_date'])
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
                'title' => trim($data['title']),
                'start_date' => trim($data['start_date']),
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
            if ($this->Survey->update($datos, $id)) {
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

    public function patchSurvey($id)
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

            if ($this->Survey->patchstate($id, $state)) {
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

    public function DeleteSurvey($id){
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Llama al modelo para realizar la eliminación de la encuesta
            $result = $this->Survey->delete($id);

            // Verificar el resultado de la eliminación
            if ($result['status']) {
                echo json_encode([
                    'status' => true,
                    'message' => 'encuesta eliminada exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al eliminar la encuesta: ' . $result['message']
                ]);
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Método de solicitud no permitido'
            ]);
        }
    }
}
