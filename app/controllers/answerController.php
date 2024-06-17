<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class AnswerController extends Controlador
{
    private $Answer;

    public function __construct()
    {
        $this->Answer = $this->modelo("answers");
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leer el cuerpo de la solicitud
        $body = file_get_contents('php://input');

        // Decodificar el JSON recibido en un array asociativo
        $data = json_decode($body, true);

        // Verificar si json_decode tuvo éxito
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'status' => false,
                'message' => 'Error al decodificar JSON'
            ]);
            return;
        }

        // Array para almacenar los resultados de las respuestas
        $results = [];

        // Iterar sobre cada elemento del array y realizar la inserción
        foreach ($data as $datos) {
            // Verificar si los datos requeridos están presentes en la solicitud
            if (!isset($datos['answer'], $datos['survey_id'], $datos['question_id'], $datos['date'])) {
                $results[] = [
                    'answer' => $datos['answer'] ?? null,
                    'survey_id' => $datos['survey_id'] ?? null,
                    'question_id' => $datos['question_id'] ?? null,
                    'date' => $datos['date'] ?? null,
                    'status' => false,
                    'message' => 'Datos incompletos en la solicitud'
                ];
                continue; // Saltar a la siguiente iteración del bucle
            }

            // Llama al modelo para realizar la inserción del usuario-cliente
            $result = $this->Answer->create($datos);

            if ($result === true) {
                $results[] = [
                    'answer' => $datos['answer'],
                    'survey_id' => $datos['survey_id'],
                    'question_id' => $datos['question_id'],
                    'date' => $datos['date'],
                    'status' => true,
                    'message' => 'Respuesta creada exitosamente'
                ];
            } else {
                // Suponiendo que $result contiene el mensaje de error de la base de datos en caso de falla
                $results[] = [
                    'answer' => $datos['answer'],
                    'survey_id' => $datos['survey_id'],
                    'question_id' => $datos['question_id'],
                    'date' => $datos['date'],
                    'status' => false,
                    'message' => 'Error al crear la respuesta: ' . $result
                ];
            }
        }

        // Retornar los resultados como un array de JSON
        echo json_encode($results);
    }
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

            if (!isset($data['survey_id']) || !isset($data['question_id']) || !isset($data['answer']) || !isset($data['date'])) {
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
