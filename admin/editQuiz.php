<?php
require_once '../assets/config/config.php';
require_once "../vendor/autoload.php";

use PhotoTech\Login;

Login::is_login($_SESSION['last_login']);

$user = Login::securityCheck();


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link rel="stylesheet" media="all" href="../assets/css/admin.css">
</head>
<body class="site">
<div id="skip"><a href="#content">Skip to Main Content</a></div>
<header class="masthead">

</header>

<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">
        The Photo Tech Guru
    </h3>

    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <a href="index.php">home</a>
        <a href="../addQuiz.php">add question</a>
        <a href="editQuiz.php">edit question</a>
        <a href="logout.php">logout</a>
    </div>
</div>

<form id="editTrivia" class="checkStyle" action="editTrivia.php" method="post" data-key="">
    <div class="remote">
        <a id="ePrev" class="btn" title="Previous Button" href="#">Prev</a>
        <h2 id="status">Record No. <span id="position"></span></h2>
        <a id="eNext" class="btn" title="Next Button" href="#">Next</a>
    </div>
    <input id="id" type="hidden" name="id" value="0">
    <input id="user_id" type="hidden" name="user_id" value="">

    <div class="select-hidden">
        <select class="select-css" tabindex="1">
            <option value="yes">Hide Question: Yes</option>
            <option value="no">Hide Question: No</option>
        </select>
    </div>
    <div class="select-category">
        <select id="category" tabindex="">
            <option value="photography">Photography</option>
            <option value="movie">Movie</option>
            <option value="space">Space</option>
        </select>
    </div>
    <div class="question">
        <label class="question_label" for="addQuestion">Content</label>
        <textarea id="addQuestion" class="question_input" name="question" tabindex="2"
                  placeholder="Add question here..."
                  autofocus>
            </textarea>
    </div>
    <div class="answer1">
        <label class="answer_one_label" for="addAnswer1">Answer 1</label>
        <input id="addAnswer1" class="answer_one_input" type="text" name="answer1" value="" tabindex="3">
    </div>
    <div class="answer2">
        <label class="answer_two_label" for="addAnswer2">Answer 2</label>
        <input class="answer_two_input" id="addAnswer2" type="text" name="quiz[answer2]" value="" tabindex="4">
    </div>

    <div class="answer3">
        <label class="answer_three_label" for="addAnswer3">Answer 3</label>
        <input class="answer_three_input" id="addAnswer3" type="text" name="quiz[answer3]" value="" tabindex="5">
    </div>

    <div class="answer4">
        <label class="answer_four_label" for="addAnswer4">Answer 4</label>
        <input class="answer_four_input" id="addAnswer4" type="text" name="quiz[answer4]" value="" tabindex="6">
    </div>

    <div class="correct">
        <label class="correct_answer_label" for="addCorrect">Answer</label>
        <input class="correct_answer_input" id="addCorrect" type="text" name="quiz[correct]" value="" tabindex="7">
    </div>

    <div class="submit-button">
        <button class="form-button" type="submit" name="submit" value="enter">submit</button>
        <button id="delete_quiz_record" class="form-button" formaction="" onclick="return confirm('Are you sure you want to delete this item?');">Delete
        </button>
    </div>


</form>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script type="text/javascript" src="../assets/js/edit.js"></script>
</body>
</html>