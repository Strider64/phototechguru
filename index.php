<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

/*
 * The Photo Tech Guru
 * Created by John R. Pepp
 * Date Created: July, 12, 2021
 * Last Revision: July 25, 2021
 * Version: 1.50 ßeta
 *
 */

use PhotoTech\CMS;
use PhotoTech\Pagination;


$displayFormat = ["gallery-container w-3 h-2", 'gallery-container w-3', 'gallery-container', 'gallery-container', 'gallery-container', 'gallery-container h-3 w-4"', 'gallery-container', 'gallery-container', 'gallery-container', 'gallery-container', 'gallery-container', 'gallery-container'];
/*
 * Using pagination in order to have a nice looking
 * website page.
 */

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $current_page = urldecode($_GET['page']);
} else {
    $current_page = 1;
}

$per_page = 12; // Total number of records to be displayed:
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
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The Photo Tech Guru</title>
    <link rel="stylesheet" media="all" href="assets/css/gallery.css">
</head>
<body class="site">
<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">
        Click on any image below
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
        <a href="blog.php">Blog</a>
        <a href="/admin/index.php">Admin</a>
        <a href="game.php">Quiz</a>
        <a href="contact.php">Contact</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>
</div>
<main class="content">
    <div class="container">

        <?php
        $count = 0;
        foreach ($cms as $record) {
            echo '<div class="' . $displayFormat[$count] . '">';
            echo '<div class="gallery-item">';
            echo '<div class="images"><img src="' . $record['image_path'] . '" alt="Photo1" data-exif="' . $record['Model'] . ' ' . $record['ExposureTime'] . ' ' . $record['Aperture'] . ' ' . $record['ISO'] . ' ' . $record['FocalLength'] . '">';
            echo '</div>';
            $count++;
            echo '<div class="title">' . '<h1 class="pictureHeading">' . $record['heading'] . '</h1>' . '<span class="exifInfo">' . $record['Model'] . '</span>' . '</div>';
            echo '</div>';
            echo '</div>';

        }
        ?>

    </div>

</main>
<aside class="sidebar">
    <?php
    $url = 'index.php';
    echo $pagination->new_page_links($url);
    ?>
</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script src="assets/js/lightbox.js"></script>
</body>
</html>
