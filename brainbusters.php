<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\ErrorHandler;
use PhotoTech\Database;

/*
 * Brain Busters 1.0 βeta
 * Created by John Pepp
 * on June 30, 2023
 */

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Brain Busters</title>
    <link rel="stylesheet" media="all" href="assets/css/brainbusters.css">
</head>
<body>
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
<main id="content" class="main">
    <div id="quiz" class="displayMessage">

        <div id="mainGame" style="display: none;">
            <div id="current" class="info-bar">
                <p>Current question is <span id="currentQuestion" data-record=""></span></p>
                <p>Your score: <span id="score">0</span></p>
            </div>
            <div id="triviaSection">
                <div id="questionBox">
                    <h2 id="question"></h2>
                    <div id="answers">
                        <button class="buttonStyle" id="ans1"></button>
                        <button class="buttonStyle" id="ans2"></button>
                        <button class="buttonStyle" id="ans3"></button>
                        <button class="buttonStyle" id="ans4"></button>
                    </div>
                    <p id="result"></p>
                </div>
                <button id="next" class="nextBtn">Next</button>
            </div>
        </div>

        <div id="categorySelector">
            <label for="category">Choose a category:</label>
            <select id="category" name="category">
                <option value="">--Please choose a category--</option>
                <option value="lego">LEGO</option>
                <option value="photography">Photography</option>
                <option value="space">Space</option>
                <option value="movie">Movies</option>
                <option value="sport">Sports</option>
            </select>
        </div>

    </div>
</main>
<script src="assets/js/brainbusters.js"></script>
</body>
</html>
