<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";


use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\LoginRepository as Login;


$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
$loginRepository = new Login($pdo);


if ($loginRepository->check_login_token()) {
    header('Location: ../dashboard.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
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
            'domain' => DOMAIN,
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        // Store the token in the user's session
        $_SESSION['login_token'] = $token;

        header('Location: ../dashboard.php');
        exit;
    } else {
        // Invalid username or password
        $error = 'Invalid username or password';
    }

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" media="all" href="../assets/css/stylesheet.css">
</head>
<body class="site">


<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">
        The Photo Tech Guru
    </h3>

    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <a href="../index.php">Home</a>
        <a href="../gallery.php">Gallery</a>
        <a href="/hangman/can_you_solve.php">Can You See?</a>
        <a href="../contact.php">Contact</a>
        <a href="/admin/login.php">Login</a>
    </div>

</div>


<form class="checkStyle" method="post" action="login.php">
    <div class="screenName">
        <label class="text_username" for="username">Username</label>
        <input id="username" class="io_username" type="text" name="username" value="" required>
    </div>

    <label class="text_password" for="password">Password</label>
    <input id="password" class="io_password" type="password" name="password" required>

    <div class="submitForm">
        <button class="submitBtn" id="submitForm" type="submit" name="submit" value="login">Login</button>
    </div>
</form>


</body>
</html>
