<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\Trivia;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

$trivia = new Trivia($pdo);

$username = 'guest';
/*
 * Cool Trivia Game
 * created by John Pepp
 * on February 17, 2023
 * modified on June 24, 2023
 */



try {
    $today = new DateTime("Now", new DateTimeZone("America/Detroit"));
} catch (Exception $e) {
}



?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Coolest Trivia Around</title>
    <link rel="stylesheet" media="all" href="assets/css/trivia.css">
    <style>

        #headerStyle {
            display: flex;
            justify-content: space-between;
            background-color: #fff;
            padding: 0.625em;
        }

        #timerStyle {
            color: #4CAF50;
        }

        #score {
            color: #4CAF50;
        }

        #percent {
            color: #4CAF50;
        }

    </style>
</head>
<body class="site">

<div class="nav">

    <div class="nav-btn" onclick="toggleNavMenu()">
        <label>
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>


    <div class="nav-links" id="nav-links">
        <?php $database->regular_navigation(); ?>
    </div>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>

</div>

<main id="content" class="main">
    <div id="openingScreen">
        <img src="assets/images/img-opening-001.jpg" alt="trivia opening graphic">
    </div>
    <div id="quiz" class="displayMessage" data-username="<?= $username ?>">
        <div class="triviaContainer" data-records=" ">
            <div id="mainGame">
                <div id="current">Current question is <span id="currentQuestion" data-record=""></span></div>
                <div id="triviaSection" data-correct="">
                    <div id="questionBox">
                        <h2 id="question"></h2>
                        <button class="buttonStyle" id="ans1"></button>
                        <button class="buttonStyle" id="ans2"></button>
                        <button class="buttonStyle" id="ans3"></button>
                        <button class="buttonStyle" id="ans4"></button>
                    </div>
                    <div id="buttonContainer"></div>
                </div>
            </div>
            <button id="next" class="nextBtn">Next</button>
        </div>
    </div>
</main>
<aside class="sidebar">
    <h1 class="catHeading">Categories</h1>
    <nav id="legoNav" class="vertical-nav">
        <ul class="disableButtons">
            <li><a class="category" id="lego" href="#">LEGO</a></li>
            <li><a class="category" id="photography" href="#">Photography</a></li>
            <li><a class="category" id="space" href="#">Space</a></li>
            <li><a class="category" id="movie" href="#">Movie</a></li>
            <li><a class="category" id="sport" href="#">Sports</a></li>
        </ul>
    </nav>
    <div id="headerStyle" data-user="">
        <p id="timerStyle">Time Left : <span id="clock"></span></p>
        <p id="score">Points: 0</p>
        <p id="percent">100% Correct</p>
    </div>

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
    <div id="finalResult">
        <h2>Game Over!</h2>
        <p><?= $username ?> ended up with <span class="totalScore"></span> points and answered <span
                    class="answeredRight"></span> right.</p>
        <a class="btn1" href="game.php" title="Quiz">Play Again?</a>
    </div>
</aside>


<script src="assets/js/trivia.js"></script>


<!--<script src="assets/js/fetchTriviaApi.js"></script>-->
</body>
</html>
