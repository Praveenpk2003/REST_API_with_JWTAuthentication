<?php


require_once '../app/models/User.php';
require_once '../app/helpers/JWT.php';
require_once '../app/helpers/Response.php';

class AuthController
{
    public function register()
    {
        $data = $GLOBALS['request']['body'] ?? [];

        if (!isset($data['email'], $data['password'], $data['name'])) {
            Response::error('Missing fields', 422);
        }

        if (User::findByEmail($data['email'])) {
            Response::error('Email already exists', 400);
        }

        User::create($data);
        Response::success('User registered', null, 201);
    }

    public function login()
    {
        $data = $GLOBALS['request']['body'];
        $user = User::findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::error('Invalid credentials', 401);
        }

        $payload = [
            'user_id'=>$user['id'],
            'email'=>$user['email'],
            'iat'=>time(),
            'exp'=>time()+env('JWT_EXPIRY')
        ];

        Response::json(['token'=>JWT::generate($payload)]);
    }
}
