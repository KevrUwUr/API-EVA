<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class UserClientController extends Controlador
{
  private $UserClient;
  private $authMiddleware;

  public function __construct()
  {
    $this->authMiddleware = new AuthMiddleware();

    $this->authMiddleware->handle($_REQUEST, function ($request) {
      $this->UserClient = $this->modelo("user_client");
    });
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

  public function postUserClient()
  {
    $jsonValidationMiddleware = new JsonValidationMiddleware(['idUser', 'idClient']);

    $jsonValidationMiddleware->handle(file_get_contents('php://input'), function ($data) {
      $results = [];
      foreach ($data as $datos) {
        $result = $this->UserClient->create($datos);
        if ($result['status'] === true) {
          $results[] = [
            'idUser' => $datos['idUser'],
            'idClient' => $datos['idClient'],
            'status' => true,
            'message' => $result['message']
          ];
        } else {
          $results[] = [
            'idUser' => $datos['idUser'],
            'idClient' => $datos['idClient'],
            'status' => false,
            'message' => 'Error al crear la asociación cliente-usuario: ' . $result['message']
          ];
        }
      }
      echo json_encode($results);
    });
  }

  public function putUserClient($id)
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

      if (!isset($data['idUser']) || !isset($data['idClient'])) {
        echo json_encode([
          'status' => false,
          'message' => 'Datos incompletos en la solicitud'
        ]);
        return;
      }

      $datos = [
        'idUser' => trim($data['idUser']),
        'idClient' => trim($data['idClient']),
      ];

      $result = $this->UserClient->update($datos, $id);
      if ($result['status'] === true) {
        echo json_encode([
          'status' => true,
          'message' => $result['message']
        ]);
      } else {
        echo json_encode([
          'status' => false,
          'message' => 'Error al actualizar la asociación cliente-usuario: ' . $result['message']
        ]);
      }
    }
  }
}
