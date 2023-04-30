<?php
require_once "assets/config/config.php";


// Store the $secret in the user's record in your database
// Display the $qrCodeUrl to the user, so they can scan it with their authenticator app

require_once "vendor/autoload.php";

//use Intervention\Image\ImageManagerStatic as Image;
use PhotoTech\{
    ErrorHandler,
    Database,
};

// Create an ErrorHandler instance
$errorHandler = new ErrorHandler();
// Set the exception handler to use the ErrorHandler instance
set_exception_handler([$errorHandler, 'handleException']);

// Create a Database instance and establish a connection
$database = new Database();

// Check to see if user is Logged In
if (!$database->check_login_token()) {
    header('location: index.php');
    exit();
}

$pdo = $database->createPDO();
$qrCodeSVG = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];

    $secret = $user['secret'];


    $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log('Invalid email address entered');
    }

    $password = $user['password'];
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password)) {
        error_log('Password does meet critera');
    }

    $confirm_password = $user['comfirm_password'];
    if ($password !== $confirm_password) {
        error_log('Passwords do not match');
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $username = filter_var($user['username'], FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO admins (email, username, password, secret) VALUES (:email, :username, :password, :secret)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':secret', $secret);
    $stmt->execute();
}
//PHPMailer Object
//$mail = new PHPMailer(true);


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Registration</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">

</head>
<body class="site">

<header class="masthead">

</header>

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

<div class="main_container">
    <div class="home_article">


        <form class="registerStyle" action="register.php" method="post">
            <input id="secret" type="hidden" name="user[secret]" value="">

            <div id="qr-code-container" class="d-none">
                <img id="qr-code-image" src="" alt="QR Code">
                <p id="qr-code-info">Scan QR Code for 2FA</p>
            </div>

            <div class="first">
                <label for="first_name">First Name</label>
                <input id="first_name" type="text" name="user[first_name]" value="" tabindex="2" required>
            </div>

            <div class="last">
                <label for="last_name">Last Name</label>
                <input id="last_name" type="text" name="user[last_name]" value="" tabindex="3" required>
            </div>

            <div class="screenName">
                <label class="text_username" for="username">Username <span class="error"> is unavailable!</span>
                </label>
                <input id="username" class="io_username" type="text" name="user[username]" value="" tabindex="4"
                       required>
            </div>

            <div class="telephone">
                <label for="phone">Phone (Optional)</label>
                <input id="phone" type="tel" name="user[phone]" value="" placeholder="555-555-5555"
                       pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" tabindex="5">

            </div>


            <div class="emailStyle">
                <label class="emailLabel" for="email">Email <span class="emailError"> is invalid</span> </label>
                <input id="email" type="email" name="user[email]" value="" tabindex="1" autofocus required>
            </div>

            <div class="password1">
                <label for="passwordBox">Password</label>
                <input class="passwordBox1" id="passwordBox" type="password" name="user[password]" value=""
                       tabindex="6" required>
                <label for="passwordVisibility">Show Passwords (private computer)</label>
                <input class="passwordBtn1" id="passwordVisibility" type="checkbox" tabindex="7">
            </div>

            <div class="password2">
                <label for="redoPassword">ReEnter Password</label>
                <input class="passwordBox2" id="redoPassword" type="password" name="user[comfirm_password]" value="" tabindex="8"
                       required>
            </div>

            <div class="birthday">
                <label for="birthday">Birthday (optional)</label>
                <input id="birthday" type="date" name="user[birthday]" value="1970-01-01" tabindex="9">
            </div>


            <div class="submitForm">
                <button class="submitBtn" id="submitForm" type="submit" name="submit" value="enter" tabindex="10">
                    Submit
                </button>
            </div>


        </form>
    </div>
    <div class="home_sidebar">
        <?php $database->showAdminNavigation(); ?>
    </div>
</div>

<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script>
    const emailInput = document.querySelector('#email');
    const qrCodeContainer = document.querySelector('#qr-code-container');
    const qrCodeImage = document.querySelector('#qr-code-image');
    const qrCodeInfo = document.querySelector('#qr-code-info');
    const secretInput = document.querySelector('#secret');

    emailInput.addEventListener('input', async (e) => {
        if (e.target.value && e.target.checkValidity()) {
            const response = await fetch('qr_code.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'email=' + encodeURIComponent(e.target.value)
            });
            const qrCodeData = await response.json();
            secretInput.value = qrCodeData.secret;
            qrCodeImage.setAttribute("src", qrCodeData.qrCodeSVG);
            qrCodeContainer.classList.remove('d-none');
        } else {
            qrCodeImage.removeAttribute('src');
            qrCodeContainer.classList.add('d-none');
        }
    });
</script>


<script>
    function passwordVisibility() {

        let passwordBox1 = document.querySelector(".passwordBox1");
        let passwordBox2 = document.querySelector(".passwordBox2");
        const fields = [passwordBox1, passwordBox2];
        fields.forEach(x => {
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

        });
    }

    document.querySelector(".passwordBtn1").addEventListener('click', passwordVisibility, false);

</script>
<script src="/assets/js/validation.js"></script>

</body>
</html>
