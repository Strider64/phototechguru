<?php
require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;
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
$per_page = 1; // Total number of records to be displayed:
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
        <a href="create.php">create</a>
        <a href="create_procedure.php">create(p)</a>
        <a href="../addQuiz.php">Quiz Maintenance</a>
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
                <a class="editButton"
                   href="edit.php?id=<?= urldecode($record['id']) ?>">Record <?= urldecode($record['id']) ?></a>
            </article>

        <?php } ?>
        <div class="flex_container">
            <?php
            $links = $pagination->links();
            echo $links;
            ?>
        </div>
    </div>

</main>
<div class="sidebar">
    <ul class="cards">
        <li class="card-item">
            <a href="https://flickr.com/photos/pepster/">
                <figure class="cards">
                    <img src="../assets/images/img_flickr_pictures.jpg" alt="Flickr" width="348" height="174">
                    <figcaption class="caption">
                        <h3 class="caption-title">Flickr Images</h3>
                    </figcaption>
                </figure>
            </a>
        </li>
        <li class="card-item">
            <a href="https://github.com/Strider64/phototechguru">
                <figure class="cards">
                    <img src="../assets/images/img_github_repository.jpg" alt="GitHub Repository">
                    <figcaption class="caption">
                        <h3 class="caption-title">GitHub Repository</h3>
                    </figcaption>
                </figure>
            </a>
        </li>
        <li class="card-item">
            <a href="https://www.facebook.com/Pepster64">
                <figure class="cards">
                    <img src="../assets/images/img-facebook-group.jpg" alt="FaceBook Group">
                    <figcaption class="caption">
                        <h3 class="caption-title">Facebook Page</h3>
                    </figcaption>
                </figure>
            </a>
        </li>
    </ul>

</div>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
</body>
</html>
