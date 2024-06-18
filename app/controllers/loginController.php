<?php
// LoginController

require_once __DIR__ . '/../../vendor/autoload.php'; // Autoload de Composer

use Firebase\JWT\JWT; // Importar la biblioteca JWT
use Firebase\JWT\Key; // Importar la biblioteca JWT

defined('BASEPATH') or exit('No se permite acceso directo');

class LoginController extends Controlador
{
  private $LoginModel;

  public function __construct()
  {
    $this->LoginModel = $this->modelo("LoginModel");
  }

  public function Auth()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $body = file_get_contents('php://input');

      // Decodificar el JSON recibido en un array asociativo
      $data = json_decode($body, true);

      if (isset($data["email"]) && isset($data["password"])) {
        $email = $data["email"];
        $password = $data["password"]; // Obtén la contraseña desde el input

        // Llama al método Login del modelo para verificar las credenciales
        $resulset = $this->LoginModel->Login($email);

        if ($resulset) {
          // Verifica la contraseña utilizando password_verify
          if (password_verify($password, $resulset->password)) {
            $id = $resulset->id;

            // Genera un token JWT utilizando el método jwt de la clase Base
            $token = Base::jwt($id, $email);
            $secretKey = "AquiVaLaKEYParaElProyectoDeEVA123456*/"; // Recomendado almacenar en archivo de configuración
            $jwt = JWT::encode($token, $secretKey, 'HS256');

            $datos = [
              'accessToken' => $jwt,
              'token_Exp' => $token['exp'],
              'nombre' => $resulset->lastname . " " . $resulset->firstname . " " . $resulset->middlename,
              'type' => $resulset->type
            ];

            if ($this->LoginModel->SaveToken($datos, $email)) {
              echo json_encode([
                'status' => true,
                'message' => 'Token actualizado exitosamente',
                'userLogin' => $datos // Devolver los datos del usuario junto con el mensaje
              ]);
            } else {
              echo json_encode([
                'status' => false,
                'message' => 'Error al actualizar el token'
              ]);
            }

            return; // Terminar la ejecución después de enviar la respuesta
          } else {
            http_response_code(401); // Unauthorized
            echo json_encode([
              'status' => 'error',
              'message' => 'Contraseña incorrecta',
            ]);
          }
        } else {
          http_response_code(404); // Not Found
          echo json_encode([
            'status' => 'error',
            'message' => 'Usuario no encontrado',
          ]);
        }
      } else {
        http_response_code(400); // Bad Request
        echo json_encode([
          'status' => 'error',
          'message' => 'Datos incompletos',
        ]);
      }
    } else {
      http_response_code(405); // Method Not Allowed
      echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido',
      ]);
    }
  }

  public function renewToken()
  {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
      http_response_code(401);
      echo json_encode(['status' => 'error', 'message' => 'Token no proporcionado']);
      return;
    }

    $currentToken = str_replace('Bearer ', '', $headers['Authorization']);

    // Validar el token actual
    if (!Base::tokenValidate($currentToken)) {
      http_response_code(401);
      echo json_encode(['status' => 'error', 'message' => 'Token no válido o expirado']);
      return;
    }

    $secretKey = "AquiVaLaKEYParaElProyectoDeEVA123456*/"; // Recomendado almacenar en archivo de configuración

    // Obtener los datos del token actual
    try {
      $decoded = JWT::decode($currentToken, new Key($secretKey, 'HS256'));
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['status' => 'error', 'message' => 'Error al decodificar el token']);
      return;
    }

    // Generar un nuevo token con una nueva fecha de expiración
    $newToken = Base::jwt($decoded->data->id, $decoded->data->email);

    // Encodificar el nuevo token
    $jwt = JWT::encode($newToken, $secretKey, 'HS256');

    // Devolver la respuesta con el nuevo token
    echo json_encode(['status' => 'success', 'message' => 'Token renovado correctamente', 'token' => $jwt]);
  }
}
