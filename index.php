<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

/*
 * The Photo Tech Guru
 * Created by John R. Pepp
 * Date Created: July, 12, 2021
 * Last Revision: September 6, 2022 @ 8:00 AM
 * Version: 3.50 ßeta
 *
 */

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;


$displayFormat = ["gallery-container w-2 h-2", 'gallery-container w-2 h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2"', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2'];
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
$total_count = CMS::countAllPage(); // Total Records in the db table:


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
$cms = CMS::page($per_page, $offset);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home Page</title>
    <link rel="stylesheet" media="all" href="assets/css/gallery.css">

</head>
<body class="site">

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
        <a href="index.php">Home</a>
        <a href="gallery.php">Gallery</a>
        <a href="/admin/index.php">Admin</a>
        <a href="game.php">Trivia</a>
        <a href="contact.php">Contact</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<a href="/admin/logout.php">Logout</a>';
        }
        ?>
    </div>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>

</div>
<main class="content">
    <div class="main_container">
        <div class="home_article">
            <?php

            foreach ($cms as $record) {

                echo '<div class="home_info">';
                echo '<h1 class="home_heading">' . $record['heading'] . '</h1>';
                echo '<img src="' . $record['image_path'] . '" alt="Home Page">';
                echo '<p class="home_paragraph">' . $record['content'] . '</p>';
                echo '</div>';

            }
            ?>
        </div>
        <div class="home_sidebar">

        </div>
    </div>


</main>
<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script src="assets/js/lightbox.js"></script>
</body>
</html>
