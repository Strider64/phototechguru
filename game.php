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
    <style>
        .button {
            display: block;
            width: 115px;
            height: 25px;
            background-color: #4E9CAF;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            line-height: 25px;
            margin: 20px 10px;
        }
        .button:hover {
            background-color: #0C2C56;
        }
    </style>
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
        <h4 class="displayTitle">Pepster's Online Trivia</h4>
        <p class="triviaInfo">I'm adding more categories to my online trivia game and hopefully make it appeal to more people! I am still keep the photography category,
        but I am adding two new categories (movie and space). I am going to make a better scoring feature and have daily challenges as well a monthly challenges!</p>
        <p>I'm still keeping the feature where a member can add questions to the online trivia database and be able to select the category of their liking, but the questions/answers will have to be approved by me first.</p>
        <p>You must register to become a Member and validate your email address before you can add questions. You don't have to add questions as you username will show up on the high scores table.</p>
        <div id="startBtn">
            <form id="game_category" action="game.php" method="post">
                <select id="category" class="select-css" name="category" tabindex="1">
                    <option selected disabled>Select a Category</option>
                    <option value="photography">Photography</option>
                    <option value="movie">Movie</option>
                    <option value="space">Space</option>
                </select>
            </form>
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
                            <div class="gauge__cover">Power 100%</div>
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
                <button class="form_button" type="submit" name="submit" value="login">Login</button>
                <a href="admin/register.php" title="register">register</a>
            </form>
        </div>
    <?php } else if (login::gameSecurityCheck()) {
        echo '<a class="button" href="addQuiz.php">Add Question</a>';
        if (login::adminCheck()) {
            echo '<a class="button" href="admin/editQuiz.php">Edit Question</a>';
        }
    }
    ?>


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
        gauge.querySelector(".gauge__cover").textContent = `Power ${Math.round(
            value * 100
        )}%`;
    }


</script>
</body>
</html>
