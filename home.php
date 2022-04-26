<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;

/*
 * Using pagination in order to have a nice looking
 * website page.
 */


$per_page = 1; // Total number of records to be displayed:
$total_count = CMS::countAllPage('blog'); // Total Records in the db table:
$current_page = $_GET['page'] ?? 1;

if (($current_page < 1)) {
    $current_page = 1;
} elseif ($current_page > ceil($total_count / $per_page)) {
    $current_page = ceil($total_count / $per_page);
}

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
$cms = CMS::page($per_page, $offset, 'blog');
//echo "<pre>" . print_r($cms, 1) . "</pre>";
//die();


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>The Photo Tech Guru</title>
    <link rel="stylesheet" media="all" href="assets/css/styles.css">
    <style>
        .hide {
            visibility: hidden;
        }
        .flex_container {
            display: flex;
            flex-flow: row nowrap;
            justify-content: space-evenly;
            align-items: stretch;
            align-content: center;
        }

        .flex_container a {
            padding: 0.625em;
            margin: 0.625em;
            text-align: center;
            text-decoration: none;
        }


    </style>
</head>
<body class="site">


<?php include_once "assets/includes/inc.nav.php"; ?>

<main class="content">
    <div class="container">
        <?php foreach ($cms as $record) { ?>
            <article class="cms">
                <img class="article_image"
                     src="<?= htmlspecialchars($record['image_path']) ?>" <?= getimagesize($record['image_path'])[3] ?>
                     alt="article image">
                <h2><?= $record['heading'] ?></h2>
                <span class="author_style">Created by <?= $record['author'] ?> on
                    <time datetime="<?= htmlspecialchars(CMS::styleTime($record['date_added'])) ?>"><?= htmlspecialchars(CMS::styleDate($record['date_added'])) ?></time>
                </span>
                <p><?= $record['content'] ?></p>
            </article>
        <?php } ?>
    </div>
    <div class="flex_container">
        <?php
        $links = $pagination->links();
        echo $links;
        ?>
    </div>
</main>

<div class="sidebar">
    <ul class="cards">
        <li class="card-item">
            <a href="https://flickr.com/photos/pepster/">
                <figure class="cards">
                    <img src="assets/images/img_flickr_pictures.jpg" alt="Flickr" width="348" height="174">
                    <figcaption class="caption">
                        <h3 class="caption-title">Flickr Images</h3>
                    </figcaption>
                </figure>
            </a>
        </li>
        <li class="card-item">
            <a href="https://github.com/Strider64/phototechguru">
                <figure class="cards">
                    <img src="assets/images/img_github_repository.jpg" alt="GitHub Repository">
                    <figcaption class="caption">
                        <h3 class="caption-title">GitHub Repository</h3>
                    </figcaption>
                </figure>
            </a>
        </li>
        <li class="card-item">
            <a href="https://www.facebook.com/Pepster64">
                <figure class="cards">
                    <img src="assets/images/img-facebook-group.jpg" alt="FaceBook Group">
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
