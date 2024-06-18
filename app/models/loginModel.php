<?php

class LoginModel
{
    private $db;

    function __construct()
    {
        $this->db = new Base; // Instancia de la clase Base para manejar la conexión PDO
    }

    public function Login($email)
    {
        $this->db->query("SELECT id, firstname, middlename, lastname, email, type, accessToken, password FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->registro(); // Devuelve un solo registro como objeto PDO
    }

    public function SaveToken($datos, $email)
    {
        $this->db->query("UPDATE users SET accessToken = :accessToken, token_Exp = :token_Exp WHERE email = :email");
        $this->db->bind(':accessToken', $datos['accessToken']);
        $this->db->bind(':token_Exp', $datos['token_Exp']);
        $this->db->bind(':email', $email);
        return $this->db->execute(); // Ejecuta la consulta y retorna true si tiene éxito, false si falla
    }
}
