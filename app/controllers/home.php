<?php
defined('BASEPATH') or exit('No se permite acceso directo');

class Home extends Controlador
{
    private $HomeModel;

    public function __construct()
    {
        $this->HomeModel = $this->modelo('HomeModel');
    }

    public function index()
    {
        $connectionStatus = $this->HomeModel->checkConnection();
        // Mostrar el estado de la conexiÃ³n

        $datos = [
            'name' => 'API EVA: ' . $connectionStatus,
        ];
        echo $datos['name'];
    }
}
