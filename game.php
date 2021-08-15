<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Login;

try {
    $today = new DateTime("Now", new DateTimeZone("America/Detroit"));
} catch (Exception $e) {
}
if (isset($_SESSION['id'])) {
    $username = Login::username();
} else {
    $username = "Guest Player";
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Photography Trivia</title>
    <link rel="stylesheet" media="all" href="assets/css/game.css">
    <script src="assets/js/game.js" defer></script>
</head>
<body class="site">
<div id="skip"><a href="#content">Skip to Main Content</a></div>

<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">
      <?= $username ?>
    </h3>

    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="photogallery.php">Gallery</a>
        <a href="/admin/index.php">Admin</a>
        <a href="game.php">Quiz</a>
        <a href="contact.php">Contact</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>
</div>

<main id="content" class="checkStyle">
    <div class="displayStatus">
        <span id="clock"></span>
        <h4 class="displayTitle">Photography Trivia</h4>
        <p class="triviaInfo">The Photo Tech Guru Trivia game has been improved to include
            a high score table of the top 5 players for that day. The person can only get 3 wrong or he or she
        will not be able to continue on and the game will end. Obviously the player will have to know something
        about photography and its history in order to go far into the game. The game is written in Vanilla JavaScript
        using Fetch to pull the questions and answers from the database table.</p>
        <p>I am still updating this trivia game. There might
            be modifications to the gameplay (rules) and I am always open to constructive critiques
            to the game.</p>
        <div id="startBtn">
            <a class="logo" id="customBtn" title="Start Button" href="game.php">Start Game</a>
        </div>
    </div>

    <div id="quiz" class="displayMessage" data-username="<?= $username ?>">
        <div class="triviaContainer" data-records=" ">
            <div id="mainGame">
                <div id="current">Question No. <span id="currentQuestion"></span></div>
                <div id="triviaSection" data-correct="">
                    <div id="questionBox">
                        <h2 id="question">What is the Question?</h2>
                    </div>
                    <div id="buttonContainer"></div>
                </div>

                <div id="headerStyle" data-user="">
                    <div class="gauge">
                        <div class="gauge__body">
                            <div class="gauge__fill"></div>
                            <div class="gauge__cover">Battery 100%</div>
                        </div>
                    </div>
                    <p id="score">0 Points</p>
                    <p id="percent">100% Correct</p>

                    <button id="next" class="nextBtn">Next</button>
                </div>

            </div>
        </div>
    </div>
    <div id="finalResult">
        <h2>Game Over!</h2>
        <p><?= $username ?> ended up with <span class="totalScore"></span> points and answered <span
                class="answeredRight"></span> right.</p>
        <a class="btn1" href="game.php" title="Quiz">Play Again?</a>
    </div>
</main>

<div class="sidebar">
    <div class="addTriviaInfo">
        <table id="scoreboard" class="styled-table">
            <thead>
            <tr class="tableTitle">
                <th colspan="2">High Scores - <?= $today->format("F j, Y") ?></th>
            </tr>
            <tr class="subTitle">
                <th>Name</th>
                <th>Points</th>
            </tr>
            </thead>
            <tbody class="anchor">

            </tbody>
        </table>
    </div>
    <?php if (!isset($_SESSION['id'])) { ?>
        <div class="info">
            <form id="gameForm" class="login" method="post" action="admin/login.php">
                <label class="text_username" for="username">Username</label>
                <input id="username" class="io_username" type="text" name="user[username]" value="" required>
                <label class="text_password" for="password">Password</label>
                <input id="password" class="io_password" type="password" name="user[hashed_password]" required>
                <button class="form_button" type="submit" name="submit" value="login">submit</button>
                <a href="admin/register.php" title="register">register</a>
            </form>
        </div>
    <?php } ?>

</div>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script>
    const gaugeElement = document.querySelector(".gauge");

    function setGaugeValue(gauge, value) {
        if (value < 0 || value > 1) {
            return;
        }

        gauge.querySelector(".gauge__fill").style.transform = `rotate(${
            value / 2
        }turn)`;
        gauge.querySelector(".gauge__cover").textContent = `Battery ${Math.round(
            value * 100
        )}%`;
    }


</script>
</body>
</html>
