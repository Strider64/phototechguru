<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";
use PhotoTech\CMS;
use PhotoTech\Pagination;
use PhotoTech\Login;

Login::is_login($_SESSION['last_login']);




if (isset($_POST['submit'])) {
    $_SESSION['page'] = $_POST['page'];
} else {
    $_SESSION['page'] = 'blog';
}

/*
 * Using pagination in order to have a nice looking
 * website page.
 */
$current_page = $_GET['page'] ?? 1; // Current Page
$per_page = 2; // Total number of records to be displayed:
$total_count = CMS::countAllPage($_SESSION['page']); // Total Records in the db table:

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($current_page, $per_page, $total_count);

/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();

/*
 * Grab the data from the CMS class method *static*
 * and put the data into an array variable.
 */
$cms = CMS::page($per_page, $offset, $_SESSION['page']);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Admin Home Page</title>
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
        <a href="../photogallery.php">home</a>
        <a href="create.php">create</a>
        <a href="addQuiz.php">Quiz Maintenance</a>
        <a href="logout.php">logout</a>
    </div>
</div>


<main id="content" class="checkStyle">
    <div class="container">
        <?php foreach ($cms as $record) { ?>
            <article class="cms">
                <img class="article_image"
                     src="<?php echo "../" . htmlspecialchars($record['image_path']); ?>" <?= getimagesize("../" . $record['image_path'])[3] ?>
                     alt="article image">
                <h2><?= $record['heading'] ?></h2>
                <span class="author_style">Created by <?= $record['author'] ?> on
                    <time datetime="<?= htmlspecialchars(CMS::styleTime($record['date_added'])) ?>"><?= htmlspecialchars(CMS::styleDate($record['date_added'])) ?></time>
                </span>
                <p><?= nl2br($record['content']) ?></p>
                <a class="form_button" href="edit.php?id=<?= urldecode($record['id']) ?>">Record <?= urldecode($record['id']) ?></a>
            </article>

        <?php }
        $url = 'photogallery.php';
        echo $pagination->new_page_links($url);
        ?>
    </div>

</main>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
