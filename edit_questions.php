<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\{
    ErrorHandler,
    Database,
    LoginRepository as Login,
    TriviaDatabaseOBJ as Trivia
};

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
$login = new Login($pdo);
if (!$login->check_login_token()) {
    header('location: index.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Edut Questions</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #addTrivia {
            margin: 1.250em auto;
            width: 80%;
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
        }

        .checkStyle {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        input, select, textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .question_css, .select_category {
            width: 100%;
        }

        .question_hidden, .category_grid_area, .question_grid_area, .answer1, .answer2, .answer3, .answer4, .correct, .submit_button_grid_area {
            grid-column: span 3;
        }

        .answerStyle {
            font-weight: bold;
        }

        input.answer_one_input,
        input.answer_two_input,
        input.answer_three_input,
        input.answer_four_input,
        input.correct_answer_input {
            box-sizing: border-box;
            width: 100%;
        }


        textarea.question_entry {
            box-sizing: border-box;
            height: 12em;
            width: 100%;
            resize: none;
        }


        .submit_button_grid_area {
            text-align: right;
        }

        button.button_style {
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            text-transform: capitalize;
            padding: 0.625em 1.250em;
            margin-top: 0.625em;
        }


        button.button_style:hover {
            background-color: #007B9A;
        }

    </style>
</head>
<body class="site">
<header class="headerStyle">

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
<section class="main_container">
    <div class="home_article">
    </div>
    <div class="home_sidebar">
        <?php $database->showAdminNavigation(); ?>
    </div>
</section>
<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
