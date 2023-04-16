<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\LoginRepository;
use PhotoTech\Trivia;

if (isset($_POST['submit'])) {
    $quiz = $_POST['quiz'];
    $quiz['hidden'] = 'yes';
    //echo "<pre>" .print_r($quiz, 1) . "</pre>";
    //die();
    $trivia = new Trivia($quiz);
    $result = $trivia->create();
}

if (!isset($_SESSION['id']) || !LoginRepository::gameSecurityCheck()) {
    header('Location: game.php');
    exit();
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Add Quiz</title>
    <link rel="stylesheet" media="all" href="assets/css/admin.css">
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
        <a href="admin/index.php">Admin</a>
        <a href="game.php">Quiz</a>
        <a href="admin/editQuiz.php">edit question</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>
</div>

<main id="content" class="checkStyle">
    <form id="addTriviaQA" class="gameForm" action="addQuiz.php" method="post">
        <input type="hidden" name="quiz[user_id]" value="<?= $_SESSION['id'] ?>">
        <input type="hidden" name="quiz[token]" value="<?= $_SESSION['token'] ?>">

        <input type="hidden" name="quiz[category]" value="photography">

        <div class="question">
            <label class="question_label" for="content">Question</label>
            <textarea class="question_input" id="content" name="quiz[question]" tabindex="2"
                      placeholder="Add question here..."
                      autofocus></textarea>
        </div>
        <div class="answer1">
            <label class="answer_one_label" for="addAnswer1">Answer 1</label>
            <input class="answer_one_input" id="addAnswer1" type="text" name="quiz[answer1]" value="" tabindex="3">
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

        <div class="category">
            <label for="category">Category</label>
            <div id="category" class="select">
                <select name="quiz[category]" id="standard-select">
                    <option value="photography">Photography</option>
                    <option value="space">Space</option>
                    <option value="movie">Movie</option>
                    <option value="sport">Sports</option>
                </select>
            </div>
        </div>


        <div class="submitBtn">
            <button class="form-button" type="submit" name="submit" value="enter">submit</button>
        </div>
    </form>
</main>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>