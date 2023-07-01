<?php
require_once '../assets/config/config.php';
require_once "../vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\LoginRepository as Login;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
$login = new Login($pdo);

$sql = "UPDATE score SET score=:score WHERE id=:id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['score' => 0, 'id' => 1]);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Can You Solve?</title>
    <link rel="stylesheet" media="all" href="../assets/css/stylesheet.css">
    <style>
        .hangman {
            display: none;
        }

        #canvasContainer {
            display: flex;
            justify-content: center;
            margin: 1.250em;
        }

        .sidebar, #hangman-canvas {
            width: 0;
            height: 0;
            display: none;
        }

        #btnContainer {
            display: flex;
            justify-content: center;
        }

        #myButton {
            display: block;
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;

            font-size: 16px;
            cursor: pointer;
            border-radius: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            margin: 4px auto;
        }

        .hangman__question {
            font-size: 0.8em;
            margin: 0 1.250em;
        }

        .hangman__guesses {
            display: none;
        }

        .footer p {
            text-align: center;
        }
    </style>
    <script>
        let initialScore = <?php echo isset($_SESSION['score']) ? $_SESSION['score'] : 0; ?>;
        let isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>

    <script type="module" src="split_picture.js"></script>
    <script type="module" src="load_image_onto_canvas.js"></script>
</head>
<body class="site">


<header class="nav">
    <input type="checkbox" class="nav-btn" id="nav-btn">
    <label for="nav-btn">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <nav class="nav-links" id="nav-links">
        <?php $database->regular_navigation(); ?>
    </nav>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>
</header>
<main class="main_container">
    <div class="home_article">
        <div id="canvasContainer">
            <canvas id="canvas" width="900" height="520">Your browser does not support Canvase</canvas>
        </div>
        <button id="myButton">Start</button>
        <div class="hangman" data-token="">
            <div class="hangman__question"></div>
            <div class="hangman__word"></div>
            <div class="hangman__guesses"></div>
            <div class="hangman__score"></div>
            <div class="hangman__remaining"></div>
            <form id="hangman-form">
                <label for="guess">Enter a letter:</label>
                <input type="text" id="guess" maxlength="1" autofocus>
            </form>
            <div class="hangman__buttons"></div>

            <button class="hangman__next">Next Question</button>
            <div class="hangman__message"></div>

        </div>
    </div>
    <div class="home_sidebar">
        <?php
        if ($login->check_login_token()) {
            $database->showAdminNavigation();
        }
        ?>
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

</main>
<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script type="module" src="can_you_solve.js"></script>
<script>
    function toggleNavMenu() {
        let navLinks = document.getElementById('nav-links');
        if (navLinks.style.height === '0px' || navLinks.style.height === '') {
            navLinks.style.height = 'calc(100vh - 3.125em)';
            navLinks.style.overflowY = 'auto';
        } else {
            navLinks.style.height = '0px';
            navLinks.style.overflowY = 'hidden';
        }
    }
</script>
</body>
</html>
