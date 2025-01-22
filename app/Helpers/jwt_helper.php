<?php

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('generate_jwt')) {
    /**
     * Generate JWT Token
     *
     * @param array $data - The payload data for the JWT
     * @param string $key - The secret key for encoding
     * @return string - The JWT token
     */
    function generate_jwt($data, $key) {
        $issuedAt = time();
        $expirationTime = $issuedAt + 86400;  // 1 day expiration
        $payload = array_merge($data, [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ]);

        // Encode the JWT token using the payload and secret key
        return JWT::encode($payload, $key, 'HS256');
    }
}


if (!function_exists('decode_jwt')) {
    /**
     * Decode JWT Token
     *
     * @param string $jwt - The JWT token to decode
     * @param string $key - The secret key for decoding
     * @return object|false - Decoded payload or false if invalid
     */
    function decode_jwt($jwt, $key) {
        try {
            // Decode the JWT token without passing headers by reference

            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return (array) $decoded; // Return decoded data as an array
        } catch (Exception $e) {
            // Log the error
            log_message('error', 'JWT Decode Error: ' . $e->getMessage());
            return false; // Return false if decoding failed
        }
    }
}
