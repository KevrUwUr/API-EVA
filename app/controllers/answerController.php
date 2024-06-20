<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class AnswerController extends Controlador
{
    private $Answer;
    private $authMiddleware;

    // Constructor de la clase
    public function __construct()
    {
        // Crear instancia del middleware de autenticación
        $this->authMiddleware = new AuthMiddleware();

        // Ejecutar el middleware de autenticación
        $this->authMiddleware->handle($_REQUEST, function ($request) {
            // Se instancia el modelo answers
            $this->Answer = $this->modelo("answers");
        });
    }

    public function Answers()
    {
        $listar = $this->Answer->list();
        echo json_encode($listar);
    }

    public function AnswerByID($id)
    {
        $listar = $this->Answer->listByID($id);
        echo json_encode($listar);
    }

    public function postAnswer()
    {
        $jsonValidationMiddleware = new JsonValidationMiddleware(['answer', 'survey_id', 'question_id', 'date']);

        $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {

            $results = [];
            foreach ($data as $datos) {
                $result = $this->Answer->create($datos);
                if ($result['status'] === true) {
                    $results[] = [
                        'survey_id' => $datos['survey_id'],
                        'question_id' => $datos['question_id'],
                        'status' => true,
                        'message' => $result['message']
                    ];
                } else {
                    $results[] = [
                        'survey_id' => $datos['survey_id'],
                        'question_id' => $datos['question_id'],
                        'status' => false,
                        'message' => 'Error al crear la asociación cliente-usuario: ' . $result['message']
                    ];
                }
            }
            echo json_encode($results);
        });
    }


    public function putAnswer($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $body = file_get_contents('php://input');
            $data = json_decode($body, true);

            if (is_null($data)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al decodificar JSON'
                ]);
                return;
            }

            if (
                !isset($data['answer'])
                || !isset($data['survey_id'])
                || !isset($data['question_id'])
                || !isset($data['date'])
            ) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ]);
                return;
            }

            $datos = [
                'survey_id' => trim($data['survey_id']),
                'question_id' => trim($data['question_id']),
                'answer' => trim($data['answer']),
                'date' => trim($data['date']),
            ];

            $result = $this->Answer->update($datos, $id);
            if ($result === true) {
                echo json_encode([
                    'status' => true,
                    'message' => 'Respuesta actualizada exitosamente'
                ]);
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Error al actualizar la respuesta: ' . $result
                ]);
            }
        }
    }
}
