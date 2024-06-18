<?php
class AuthMiddleware
{
    public function handle($request, $next)
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            if (Base::tokenValidate($token)) {
                return $next($request);
            }
        }
        http_response_code(401); // Unauthorized
        echo json_encode(['status' => 'error', 'message' => 'Token no válido o expirado']);
        exit;
    }
}
