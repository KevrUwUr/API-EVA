<?php
    defined('BASEPATH') or exit('No se permite acceso directo');
    class Home extends Controlador{
        public function index(){
            $datos = [
                'name' => 'API REST CON PHP - STORE ✌',
            ];
            echo $datos['name'];
        }   
    }
?>