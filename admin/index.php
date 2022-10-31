<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;
use PhotoTech\Login;

Login::is_login($_SESSION['last_login']);

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $current_page = urldecode($_GET['page']);
} else {
    $current_page = 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
} else {
    $category = 'general';
}

$per_page = 12; // Total number of records to be displayed:
$total_count = CMS::countAllPage('blog', $category); // Total Records in the db table:
//echo "<pre>" . print_r($total_count, 1) . "</pre>";
//die();

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($current_page, $per_page, $total_count);


/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();
//echo "<pre>" . print_r($offset, 1) . "</pre>";
//die();


/*
 * Grab the data from the CMS class method *static*
 * and put the data into an array variable.
 */
$cms = CMS::page($per_page, $offset, 'blog', $category);
//echo "<pre>" . print_r($cms, 1) . "</pre>";
//die();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" media="all" href="../assets/css/styles.css">
</head>
<body class="site">
<header class="header">

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
        <a href="../index.php">home</a>
        <a href="gallery_create.php">create</a>
        <a href="../addQuiz.php">Quiz Maintenance</a>
        <a href="logout.php">logout</a>
    </div>
</div>


<main id="content" class="checkStyle">
    <div class="container">
        <?php foreach ($cms as $record) { ?>
            <article class="cms">
                <img src="<?= "../" . $record['image_path'] ?>" alt="<?= $record['heading'] ?>">
                <h2><?= $record['heading'] ?></h2>
                <span class="author_style">Created by <?= $record['author'] ?> on
                    <time datetime="<?= htmlspecialchars(CMS::styleTime($record['date_added'])) ?>"><?= htmlspecialchars(CMS::styleDate($record['date_added'])) ?></time>
                </span>
                <p><?= nl2br($record['content']) ?></p>
                <a class="editButton"
                   href="edit.php?id=<?= urldecode($record['id']) ?>">Record <?= urldecode($record['id']) ?></a>
            </article>

        <?php } ?>
        <div class="flex_container">
            <?= $pagination->links('gallery.php'); ?>
        </div>
    </div>

</main>
<div class="sidebar">
    <form id="formData" class="checkStyle" action="index.php" method="post">
        <div class="category-style">
            <select id="category" class="select-css" name="category" tabindex="1">
                <option selected disabled>Select a Category</option>
                <option value="general" selected>General</option>
                <option value="lego">LEGO</option>
                <option value="halloween">Halloween</option>
                <option value="landscape">Landscape</option>
                <option value="wildlife">Wildlife</option>
            </select>
        </div>
        <div class="submit-button">
            <button class="form-button" type="submit" name="submit" value="enter">submit</button>
        </div>
    </form>
</div>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
