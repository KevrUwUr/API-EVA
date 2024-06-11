<?php
////////////////////////////////////
// Leemos el archivo de configuración
////////////////////////////////////
$Config = parse_ini_file('config.ini', true);

////////////////////////////////////
// Conexion a la base de datos
////////////////////////////////////
define('DB_HOST', $Config['database']['host']);
define('DB_USERNAME', $Config['database']['username']);
define('DB_PASSWORD', $Config['database']['password']);
define('DB_SCHEMA', $Config['database']['schema']);
/////////////////////////////////////
// Ruta de la aplicacion
/////////////////////////////////////
define('RUTA_SEE',dirname(dirname(__FILE__)) . "/");

////////////////////////////////////
// Ruta de la URL
// Ejemplo http://localhost/nombreapp
////////////////////////////////////
define('URL_SEE', $Config['application']['route']);

//////////////////////////////////////
// Valores configuracion
/////////////////////////////////////
//error_reporting(0);

define('ERROR_REPORTING_LEVEL', -1);
define('WR_DEBUG', false);

/////////////////////////////////////
// Datos del Sitio
/////////////////////////////////////
define('NAME_SEE', $Config['application']['name']);
define('VERSION_SEE', $Config['application']['version']);
