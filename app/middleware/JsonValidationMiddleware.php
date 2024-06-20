<?php

class JsonValidationMiddleware
{
    private $requiredFields;

    public function __construct($requiredFields)
    {
        $this->requiredFields = $requiredFields;
    }

    public function handle($request, $next)
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (is_null($data)) {
            echo json_encode(['status' => false, 'message' => 'Error al decodificar JSON']);
            exit;
        }

        // Permitir tanto objetos individuales como listas de objetos
        if (isset($data[0]) && is_array($data[0])) {
            // Es una lista de objetos
            foreach ($data as $item) {
                foreach ($this->requiredFields as $field) {
                    if (!isset($item[$field])) {
                        echo json_encode(['status' => false, 'message' => "El campo $field es obligatorio en todos los elementos de la lista"]);
                        exit;
                    }
                }
            }
        } else {
            // Es un objeto individual
            foreach ($this->requiredFields as $field) {
                if (!isset($data[$field])) {
                    echo json_encode(['status' => false, 'message' => "El campo $field es obligatorio"]);
                    exit;
                }
            }
        }

        return $next($data);
    }
}
