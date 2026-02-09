<?php

require_once '../app/models/User.php';
require_once '../app/helpers/JWT.php';

class AuthController
{
    public function register()
    {
        $data = $GLOBALS['request']['body'] ?? [];

        if (!isset($data['email'], $data['password'], $data['name'])) {
            http_response_code(422);
            echo json_encode(['error'=>'Missing fields']);
            return;
        }

        User::create($data);
        echo json_encode(['message'=>'User registered']);
    }

    public function login()
    {
        $data = $GLOBALS['request']['body'];
        $user = User::findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['error'=>'Invalid credentials']);
            return;
        }

        $payload = [
            'user_id'=>$user['id'],
            'email'=>$user['email'],
            'iat'=>time(),
            'exp'=>time()+env('JWT_EXPIRY')
        ];

        echo json_encode(['token'=>JWT::generate($payload)]);
    }
}
