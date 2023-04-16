<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;


$errorHandler = new ErrorHandler();
$database = new Database($errorHandler);

$pdo = $database->createPDO();
$loginRepository = new LoginRepository($pdo);

$username = 'Strider64';
$password = 'Dpsimfm1964!';

// Verify the username and password
if ($loginRepository->verify_credentials($username, $password)) {
    // Generate a unique token
    $token = bin2hex(random_bytes(32));

    // Store the token in the user's database record (or other persistent storage mechanism)
    $loginRepository->store_token_in_database($_SESSION['user_id'], $token);

    // Set a cookie with the token and a 6-month expiration time
    setcookie('login_token', $token, [
        'expires' => strtotime('+6 months'),
        'path' => '/',
        'domain' => 'www.phototechguru.com',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    // Store the token in the user's session
    $_SESSION['login_token'] = $token;
    //echo 'It works!' . $token . "<br>";
    // Redirect the user to the dashboard or home page
    //header('Location: dashboard.php');
    //exit;
} else {
    // Invalid username or password
    $error = 'Invalid username or password';
}
