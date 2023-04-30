<?php
use Firebase\JWT\JWT;
// auth_middleware.php

// Check if the JWT token exists in the cookie
if (isset($_COOKIE['jwt_token'])) {
    $jwt_token = $_COOKIE['jwt_token'];
    $jwt_secret = JWT_SECRET; // The same secret key used when encoding the token

    // Split the JWT token into its components
    $token_parts = explode('.', $jwt_token);

    // Decode the header and payload
    $header = json_decode(base64_decode($token_parts[0]), true);
    $payload = json_decode(base64_decode($token_parts[1]), true);

    error_log("Header: " . json_encode($header));
    error_log("Payload: " . json_encode($payload));

    // Decode the JWT token
    $decoded = JWT::decode($jwt_token, $jwt_secret, ['HS256']);

    // Access user data from the decoded token
    $user_id = $decoded->user_id;
    $username = $decoded->username;

    error_log("Decoded user_id: " . $user_id);
    error_log("Decoded username: " . $username);

} else {
    // If the JWT token is not found, redirect the user to the login page
    header('Location: admin/login.php');
    exit;
}


