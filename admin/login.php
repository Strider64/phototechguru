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
    <style>
        /* Basic styling resets */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Container for the form */
        .checkStyle {
            width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #f0f0f0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-family: Arial, sans-serif;
        }

        /* Style labels */
        .text_username,
        .text_password {
            display: block;
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        /* Style input fields */
        .io_username,
        .io_password {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }

        /* Style input fields on focus */
        .io_username:focus,
        .io_password:focus {
            border-color: #0b79f7;
            outline: none;
        }

        /* Style submit button */
        .submitBtn {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #0b79f7;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Change button color on hover */
        .submitBtn:hover {
            background-color: #0969cc;
        }

    </style>
</head>
<body class="site">
<div class="nav">

    <input type="checkbox" id="nav-check">


    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <?php $database->regular_navigation(); ?>
    </div>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>

</div>

<main class="content">
    <div class="main_container">
        <div class="home_article">
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
        </div>
        <div class="home_sidebar">
            <ul class="cards">
                <li class="card-item">
                    <a href="https://flickr.com/photos/pepster/">
                        <figure class="cards">
                            <img src="../assets/images/img_flickr_pictures.jpg" alt="Flickr" width="348" height="174">
                            <figcaption class="caption">
                                <h3 class="caption-title">Flickr Images</h3>
                            </figcaption>
                        </figure>
                    </a>
                </li>
                <li class="card-item">
                    <a href="https://github.com/Strider64/phototechguru">
                        <figure class="cards">
                            <img src="../assets/images/img_github_repository.jpg" alt="GitHub Repository">
                            <figcaption class="caption">
                                <h3 class="caption-title">GitHub Repository</h3>
                            </figcaption>
                        </figure>
                    </a>
                </li>
                <li class="card-item">
                    <a href="https://www.facebook.com/Pepster64">
                        <figure class="cards">
                            <img src="../assets/images/img-facebook-group.jpg" alt="FaceBook Group">
                            <figcaption class="caption">
                                <h3 class="caption-title">Facebook Page</h3>
                            </figcaption>
                        </figure>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</main>
</div>

<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>

</body>
</html>
