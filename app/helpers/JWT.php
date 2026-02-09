<?php

class JWT
{
    public static function generate($payload)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $headerEncoded = self::base64UrlEncode($header);
        
        $json = json_encode($payload);
        if ($json === false) {
            throw new Exception('Failed to encode JWT payload: ' . json_last_error_msg());
        }
        $payloadEncoded = self::base64UrlEncode($json);
        
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", env('JWT_SECRET'), true);
        $signatureEncoded = self::base64UrlEncode($signature);
        
        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public static function validate($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            file_put_contents('../public/jwt_debug.log', date('Y-m-d H:i:s') . " - Invalid token parts: " . count($parts) . "\n", FILE_APPEND);
            return false;
        }
        
        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $signature = self::base64UrlDecode($signatureEncoded);
        $expectedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", env('JWT_SECRET'), true);

        if (!hash_equals($signature, $expectedSignature)) {
            file_put_contents('../public/jwt_debug.log', date('Y-m-d H:i:s') . " - Signature Mismatch.\nPassed: $signatureEncoded\nComputed: " . self::base64UrlEncode($expectedSignature) . "\nSecret: " . substr(env('JWT_SECRET'), 0, 3) . "***\n", FILE_APPEND);
            return false;
        }

        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);
        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            file_put_contents('../public/jwt_debug.log', date('Y-m-d H:i:s') . " - Token expired or invalid payload.\n", FILE_APPEND);
            return false;
        }

        return $payload;
    }

    private static function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode($data)
    {
        $urlUnsafeData = str_replace(['-', '_'], ['+', '/'], $data);
        $remainder = strlen($urlUnsafeData) % 4;
        if ($remainder) {
            $urlUnsafeData .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode($urlUnsafeData);
    }
}
