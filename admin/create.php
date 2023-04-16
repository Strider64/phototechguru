<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";


use PhotoTech\LoginRepository;
use PhotoTech\CMS;

LoginRepository::is_login($_SESSION['last_login']);

LoginRepository::securityCheck();

$save_result = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST['cms'];

    try {
        $today = $todayDate = new DateTime('today', new DateTimeZone("America/Detroit"));
    } catch (Exception $e) {
    }
    $data['date_updated'] = $data['date_added'] = $today->format("Y-m-d H:i:s");

    $cms = new CMS($data);
    $result = $cms->create();
    if ($result) {
        header("Location: gallery.php");
        exit();
    }
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" media="all" href="../assets/css/admin.css">
</head>
<body class="site">

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
        <a href="index.php">Home</a>
        <a href="create.php">Create</a>
        <a href="../addQuiz.php">Add Questions</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="sidebar">

</div>

<form id="formData" class="checkStyle" action="create.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="cms[user_id]" value="3">
    <input type="hidden" name="cms[author]" value="<?= LoginRepository::full_name() ?>">
    <input type="hidden" name="action" value="upload">
    <div class="file-style">
        <input id="file" class="file-input-style" type="file" name="image">
        <label for="file">Select file</label>
    </div>
    <label>
        <select class="select-css" name="cms[page]">
            <option value="index" selected>Home</option>
            <option value="blog">Blog</option>
            <option value="about">About</option>
        </select>
    </label>
    <div class="heading-style">
        <label class="heading_label_style" for="heading">Heading</label>
        <input class="enter_input_style" id="heading" type="text" name="cms[heading]" value="" tabindex="1" required
               autofocus>
    </div>
    <div class="content-style">
        <label class="text_label_style" for="content">Content</label>
        <textarea class="text_input_style" id="content" name="cms[content]" tabindex="2"></textarea>
    </div>
    <div class="submit-button">
        <button class="form-button" type="submit" name="submit" value="enter">submit</button>
    </div>
</form>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>

</body>
</html>