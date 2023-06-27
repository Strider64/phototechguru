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

        .checkStyle {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 80%;
            border-radius: 8px;
            background-color: #f8f8f8;
            padding: 20px;
            margin: 1.250em auto;
        }

        .remote {
            display: flex;
            grid-column: span 3; /* added this line */
            justify-content: space-between;
            width: 100%; /* added this line */
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }

        .remote_item {
            flex: 1;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #007B9A;
        }

        #status {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }

        #status #position {
            font-weight: bold;
        }


        input, select, textarea {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .question_css, .select_category {
            width: 100%;
        }

        .question_hidden,
        .select-css,
        .question_grid_area,
        .answer1,
        .answer2,
        .answer3,
        .answer4,
        .correct,
        .submit_button_grid_area {
            grid-column: span 3;
            box-sizing: border-box;
            color: #2E2E2E;
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
            background-color: #007bff;
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
        <form id="editTrivia" class="checkStyle" action="edit_questions.php" method="post" data-key="">
            <input id="id" type="hidden" name="id" value="">
            <input id="user_id" type="hidden" name="user_id" value="">

            <div class="remote">
                <div class="remote_item">
                    <a id="ePrev" class="btn" title="Previous Button" href="#">Prev</a>
                </div>
                <div class="remote_item">
                    <h2 id="status">Record No. <span id="position"></span></h2>
                </div>
                <div class="remote_item">
                    <a id="eNext" class="btn" title="Next Button" href="#">Next</a>
                </div>
            </div>


            <select class="select-css" name="hidden" tabindex="1">
                <option value="yes">Hide Question: Yes</option>
                <option value="no" selected>Hide Question: No</option>
            </select>


            <select id="category" class="select-css" name="category" tabindex="">
                <option value="lego">LEGO</option>
                <option value="photography">Photography</option>
                <option value="movie">Movie</option>
                <option value="space">Space</option>
                <option value="sport">Sports</option>
            </select>

            <div class="question_grid_area">
                <label class="question_label" for="addQuestion">Content</label>
                <textarea id="addQuestion" class="question_entry" name="question" tabindex="2"
                          placeholder="Add question here..."
                          autofocus>
            </textarea>
            </div>
            <div class="answer1">
                <label class="answer_one_label" for="addAnswer1">Answer 1</label>
                <input id="addAnswer1" class="answer_one_input" type="text" name="ans1" value="" tabindex="3">
            </div>
            <div class="answer2">
                <label class="answer_two_label" for="addAnswer2">Answer 2</label>
                <input class="answer_two_input" id="addAnswer2" type="text" name="ans2" value="" tabindex="4">
            </div>

            <div class="answer3">
                <label class="answer_three_label" for="addAnswer3">Answer 3</label>
                <input class="answer_three_input" id="addAnswer3" type="text" name="ans3" value="" tabindex="5">
            </div>

            <div class="answer4">
                <label class="answer_four_label" for="addAnswer4">Answer 4</label>
                <input class="answer_four_input" id="addAnswer4" type="text" name="ans4" value="" tabindex="6">
            </div>

            <div class="correct">
                <label class="correct_answer_label" for="addCorrect">Answer</label>
                <input class="correct_answer_input" id="addCorrect" type="text" name="correct" value="" tabindex="7">
            </div>

            <div class="submit_button_grid_area">
                <button id="delete_quiz_record" class="button_style" formaction=""
                        onclick="return confirm('Are you sure you want to delete this item?');">Delete
                </button>
                <button id="submit_button" class="button_style" type="submit" name="submit" value="enter">submit</button>
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
<script src="assets/js/edit.js"></script>
</body>
</html>
