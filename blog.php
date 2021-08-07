<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\CMS;
use PhotoTech\Pagination;

/*
 * Using pagination in order to have a nice looking
 * website page.
 */


$current_page = $_GET['page'] ?? 1;


$per_page = 2; // Total number of records to be displayed:
$total_count = CMS::countAllPage('blog'); // Total Records in the db table:


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


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>The Photo Tech Guru Blog</title>
    <link rel="stylesheet" media="all" href="assets/css/styles.css">
</head>
<body class="site">

<header class="masthead">

</header>

<?php include_once "assets/includes/inc.nav.php"; ?>

<main  class="content">
    <div class="container">
        <?php foreach ($cms as $record) { ?>
            <article class="cms">
                <img class="article_image"
                     src="<?php echo htmlspecialchars($record['image_path']); ?>" <?= getimagesize($record['image_path'])[3] ?>
                     alt="article image">
                <h2><?= $record['heading'] ?></h2>
                <span class="author_style">Created by <?= $record['author'] ?> on
                    <time datetime="<?= htmlspecialchars(CMS::styleTime($record['date_added'])) ?>"><?= htmlspecialchars(CMS::styleDate($record['date_added'])) ?></time>
                </span>
                <p><?= nl2br($record['content']) ?></p>
            </article>
        <?php } ?>

    </div>
</main>

<div class="sidebar">
    <?php
    $url = 'blog.php';
    echo $pagination->new_page_links($url);
    ?>
</div>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>

</body>
</html>
