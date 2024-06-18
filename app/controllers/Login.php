<?php
// Se define que no se permite acceso directo al archivo
defined('BASEPATH') or exit('No se permite acceso directo');

// Se declara la clase Login que extiende de Controlador
class Login extends Controlador
{

    private $LoginModel;
    
    // Constructor de la clase
    public function __construct()
    {
        // Se instancia el modelo LoginModel
        $this->LoginModel = $this->modelo("LoginModel");
    }

    // Método para cargar la vista principal del inicio de sesión
    public function index()
    {
        echo ('Conexion BD');
    }
}
