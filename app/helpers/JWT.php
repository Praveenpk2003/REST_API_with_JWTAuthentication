<?php

class JWT
{
    public static function generate($payload)
    {
        $header = base64_encode(json_encode(['alg'=>'HS256','typ'=>'JWT']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", env('JWT_SECRET'), true);
        return "$header.$payload." . base64_encode($signature);
    }

    public static function validate($token)
    {
        [$h,$p,$s] = explode('.', $token);
        $check = base64_encode(hash_hmac('sha256', "$h.$p", env('JWT_SECRET'), true));

        if (!hash_equals($check, $s)) return false;

        $payload = json_decode(base64_decode($p), true);
        if ($payload['exp'] < time()) return false;

        return $payload;
    }
}
