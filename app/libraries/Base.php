<?php

require_once __DIR__ . '/../../vendor/autoload.php'; // Autoload de Composer

use Firebase\JWT\JWT; // Importar Key para la decodificación
use Firebase\JWT\Key; // Importar Key para la decodificación


defined('BASEPATH') or exit('No se permite acceso directo');

// Clase para conexion a la base de datos y ejecutar consultas, utilizando PDO
class Base
{
    private $host = DB_HOST;
    private $user = DB_USERNAME;
    private $pass = DB_PASSWORD;
    private $name = DB_SCHEMA;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        // Configurar conexion
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;
        $opciones = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Crear una instancia de PDO
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $opciones);
            $this->dbh->exec('set names utf8');
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Prepara la consulta
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Vincula la consulta a BIND
    public function bind($parametro, $valor, $tipo = null)
    {
        if (is_null($tipo)) {
            switch (true) {
                case is_int($valor):
                    $tipo = PDO::PARAM_INT;
                    break;
                case is_bool($valor):
                    $tipo = PDO::PARAM_BOOL;
                    break;
                case is_null($valor):
                    $tipo = PDO::PARAM_NULL;
                    break;
                default:
                    $tipo = PDO::PARAM_STR;
                    break;
            }
        }
        $this->stmt->bindValue($parametro, $valor, $tipo);
    }

        // MÃ©todo para obtener errores
        public function getError() {
            return $this->error ? 'Error al conectar a la base de datos: ' . $this->error : 'ConexiÃ³n a la base de datos exitosa!';
        }

    // Ejecuta la consulta
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Obtiene los registros
    public function registros()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Obtiene un registro
    public function registro()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Obtiene el total de filas que coinciden
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    // Método para obtener una sola fila del resultado
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para generar un token JWT
    static public function jwt($id, $email)
    {
        $time = time();
        $token = array(
            "iat" => $time,
            "exp" => $time + (60 * 30), // 5 minutos
            "data" => [
                "id" => $id,
                "email" => $email
            ]
        );

        return $token;
    }

    // Método para validar un token JWT
    static public function tokenValidate($token)
    {
        $secretKey = "AquiVaLaKEYParaElProyectoDeEVA123456*/"; // Recomendado almacenar en archivo de configuración

        try {
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Crear una nueva instancia para ejecutar la consulta
            $db = new Base;
            $db->query("SELECT token_Exp FROM users WHERE accessToken = :accessToken");
            $db->bind(':accessToken', $token);
            $user = $db->single();

            if ($user) {
                $currentTime = time();
                if ($user['token_Exp'] > $currentTime) {
                    return true;
                } else {
                    return false; // Token expirado
                }
            } else {
                return false; // Token no encontrado en la base de datos
            }
        } catch (Exception $e) {
            return false; // Token no válido
        }
    }
}
