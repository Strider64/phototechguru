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

//echo "<pre>" . print_r($_SESSION['securityLevel'], 1) . "</pre>";
//die();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz = $_POST['quiz'];
    $timezone = new DateTimeZone('America/Detroit'); // Use your timezone here
    $today = new DateTime('now', $timezone);
    $quiz['date_added'] = $today->format("Y-m-d H:i:s");
    $trivia = new Trivia($pdo, $quiz);
    $result = $trivia->create();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>New Questions</title>
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
        <form id="addTrivia" class="checkStyle" action="new_questions.php" method="post">
            <input type="hidden" name="quiz[user_id]" value="<?= $_SESSION['user_id'] ?>">
            <div class="question_hidden">
                <select class="question_css" name="quiz[hidden]" tabindex="1">
                    <option value="yes">Hide Question: Yes</option>
                    <option value="no" selected>Hide Question: No</option>
                </select>
            </div>
            <div class="category_grid_area">
                <select id="category" class="select_category" name="quiz[category]" tabindex="2">
                    <option value="lego">LEGO</option>
                    <option value="photography">Photography</option>
                    <option value="movie">Movie</option>
                    <option value="space">Space</option>
                    <option value="sport">Sports</option>
                </select>
            </div>

            <div class="question_grid_area">
                <label class="question_label" for="content">Question</label>
                <textarea class="question_entry" id="content" name="quiz[question]" tabindex="3"
                          placeholder="Add question here..."
                          autofocus></textarea>
            </div>
            <div class="answer1">
                <label class="answer_one_label answerStyle" for="addAnswer1">Answer 1</label>
                <input class="answer_one_input" id="addAnswer1" type="text" name="quiz[ans1]" value="" tabindex="4">
            </div>
            <div class="answer2">
                <label class="answer_two_label answerStyle" for="addAnswer2">Answer 2</label>
                <input class="answer_two_input" id="addAnswer2" type="text" name="quiz[ans2]" value="" tabindex="5">
            </div>

            <div class="answer3">
                <label class="answer_three_label answerStyle" for="addAnswer3">Answer 3</label>
                <input class="answer_three_input" id="addAnswer3" type="text" name="quiz[ans3]" value="" tabindex="6">
            </div>

            <div class="answer4">
                <label class="answer_four_label answerStyle" for="addAnswer4">Answer 4</label>
                <input class="answer_four_input" id="addAnswer4" type="text" name="quiz[ans4]" value="" tabindex="7">
            </div>

            <div class="correct">
                <label class="correct_answer_label" for="addCorrect">Correct Answer</label>
                <input class="correct_answer_input" id="addCorrect" type="text" name="quiz[correct]" value=""
                       tabindex="8">
            </div>

            <div class="submit_button_grid_area">
                <button class="button_style" type="submit" name="submit" value="enter" tabindex="9">submit</button>
            </div>
        </form>
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
?>
</body>
</html>
