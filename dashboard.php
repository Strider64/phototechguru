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

use PhotoTech\ErrorHandler;
use PhotoTech\Database;

use PhotoTech\ImageContentManager;
use PhotoTech\Links;
use PhotoTech\LoginRepository as Login;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

$args = [];
// New Instance of CMS Class

$gallery = new ImageContentManager($pdo, $args);

// New Instance of Login Class
if (!$database->check_login_token()) {
    header('location: index.php');
    exit();
}


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

$per_page = 2; // Total number of records to be displayed:
$total_count = $gallery->countAllPage('wildlife'); // Total Records in the category:

//echo $total_count . "<br>";

// Grab Total Pages
$total_pages = $gallery->total_pages($total_count, $per_page);



/* Grab the offset (page) location from using the offset method */
$offset = $gallery->offset($per_page, $current_page);

$links = new Links($current_page, $per_page, $total_count);
$records = $gallery->page($per_page, $offset, 'gallery', 'wildlife');
//echo "<pre>" . print_r($records, 1) . "</pre>";
//die();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">
    <style>
        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }

        .pagination > li {
            display: inline;
        }

        .pagination > li > a,
        .pagination > li > span {
            position: relative;
            float: left;
            font-size: 1.0em;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .pagination > li:first-child > a,
        .pagination > li:first-child > span {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }

        .pagination > li:last-child > a,
        .pagination > li:last-child > span {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        .pagination > li > a:hover,
        .pagination > li > a:focus {
            color: #23527c;
            background-color: #eee;
            border-color: #ddd;
        }

        .pagination > .active > a,
        .pagination > .active > span,
        .pagination > .active > a:hover,
        .pagination > .active > span:hover,
        .pagination > .active > a:focus,
        .pagination > .active > span:focus {
            z-index: 2;
            color: #fff;
            cursor: default;
            background-color: #337ab7;
            border-color: #337ab7;
        }

        .pagination > li > span {
            display: inline-block; /* Change this line */
            padding: 6px 12px;
            color: #999;
            background-color: #fff;
            border: 1px solid #ddd;
            box-sizing: border-box;
            min-height: 1.42em; /* Add this line */
            line-height: 1.42em; /* Add this line */
        }


        }
        .pagination > li > span::before {
            content: '...';
            display: inline-block;
            vertical-align: middle;
            line-height: 34px;
        }


    </style>


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
        <?php $database->regular_navigation(); ?>
    </div>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>

</div>

<div class="main_container">
    <div class="home_article">
        <?php

        foreach ($records as $record) {

            echo '<div class="home_info">';
            echo '<h1 class="home_heading">' . $record['heading'] . '</h1>';
            echo '<img src="' . $record['image_path'] . '" alt="' . $record['heading'] . '">';
            echo '<p class="home_paragraph">' . $record['content'] . '</p>';
            echo '</div>';

        }
        ?>
    </div>
    <div class="home_sidebar">
        <?php echo $links->display_links(); ?>
        <?php $database->showAdminNavigation(); ?>
    </div>
</div>


<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script src="assets/js/lightbox.js"></script>
</body>
</html>
