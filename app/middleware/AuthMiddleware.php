<?php

require_once '../app/helpers/JWT.php';

class AuthMiddleware
{
    public static function handle(&$request)
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Missing token']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $data = JWT::validate($token);

        if (!$data) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }

        $request['user'] = $data;
    }
}
