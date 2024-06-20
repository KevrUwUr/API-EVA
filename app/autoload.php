<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/////////////////////////////////////////////////
// Cargamos las librerías y middleware necesarios
/////////////////////////////////////////////////
require_once 'config/configurar.php';

/////////////////////////////////////////////////
// Autoload para cargar clases automáticamente
/////////////////////////////////////////////////
spl_autoload_register(function ($className) {
    $classFile = '';

    // Ruta base de las carpetas donde se encuentran los archivos
    $baseDirs = [
        'libraries/',
        'middleware/',
        'models/'
    ];

    // Intentamos cargar la clase desde cada directorio base
    foreach ($baseDirs as $dir) {
        $classFile = __DIR__ . '/../app/' . $dir . $className . '.php';
        if (file_exists($classFile)) {
            require_once $classFile;
            return;
        }
    }

    // Si la clase no se encuentra en ninguno de los directorios base
    die('Clase ' . $className . ' no encontrada en ' . $classFile);
});
