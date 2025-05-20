<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiHelper {
    public static function setCorsHeaders() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    public static function generateToken($userId) {
        $key = 'your-secret-key'; // Replace with a secure key
        $payload = [
            'user_id' => $userId,
            'iat' => time(),
            'exp' => time() + 3600, // Token expires in 1 hour
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function verifyToken($token) {
        try {
            $key = 'your-secret-key'; // Same key as above
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return $decoded->user_id;
        } catch (Exception $e) {
            return null;
        }
    }
}