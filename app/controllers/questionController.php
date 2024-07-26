<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Home que extiende de Controlador
class QuestionController extends Controlador
{
    private $Question;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo Question
            $this->Question = $this->modelo("questions");
        });
    }

    public function Questions()
    {
        $listar = $this->Question->list();
        echo json_encode($listar);
    }

    public function QuestionByID($id)
    {
        $listar = $this->Question->listByID($id);
        echo json_encode($listar);
    }

    // Método para insertar un usuario
    public function postQuestion()
    {
        // Usar middleware JsonValidationMiddleware
        $jsonValidationMiddleware = new JsonValidationMiddleware(['question', 'survey_id', 'type', 'percentage', 'frm_option', 'conditional', 'id_conditional', 'conditional_answer', 'section', 'selected_answer', 'select_option']);

        // Manejar la validación y procesamiento del usuario
        $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {

            // Asignar los valores del array $data al array $datos
            $datos = [
                'question' => trim($data['question']),
                'survey_id' => trim($data['survey_id']),
                'type' => trim($data['type']),
                'percentage' => trim($data['percentage']),
                'frm_option' => trim($data['frm_option']),
                'conditional' => trim($data['conditional']),
                'id_conditional' => trim($data['id_conditional']),
                'conditional_answer' => trim($data['conditional_answer']),
                'section' => trim($data['section']),
                'selected_answer' => trim($data['selected_answer']),
                'select_option' => trim($data['select_option']),
            ];

            // Llama al modelo para realizar la inserción del usuario
            $result = $this->Question->create($datos);
            if ($result == true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Pregunta creada exitosamente'
                ]);
            } else {
                // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al crear la pregunta: ' . $result['message']                    
                ]);
            }
        });
    }

    public function putQuestion($id)
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

            // Limpiar los datos recibidos
            $datos = [];
            foreach ($data as $key => $value) {
                $datos[$key] = trim($value);
            }

            // Remover campos vacíos para no actualizar con datos vacíos
            $datos = array_filter($datos, function ($value) {
                return $value !== '';
            });

            // Llama al modelo para realizar la actualización de la pregunta
            $result = $this->Question->update($datos, $id);
            echo $result;
        }
    }


    public function deleteQuestion($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Llama al modelo para realizar la eliminación de la pregunta
            $result = $this->Question->delete($id);

            // Verificar el resultado de la eliminación
            if ($result['status']) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Pregunta eliminada exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al eliminar la pregunta: ' . $result['message']
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
