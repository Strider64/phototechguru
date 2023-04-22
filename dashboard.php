<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

// dashhboard.php
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



$gallery = new ImageContentManager($pdo);

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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
        $total_count = $gallery->countAllPage($category);
    } else {
        error_log('Category is not set in the GET data');
        $category = 'wildlife';
        $total_count = $gallery->countAllPage($category);
    }
} else {
    try {
        $category = 'wildlife';
        $total_count = $gallery->countAllPage($category);
    } catch (Exception $e) {
        error_log('Error while counting all pages: ' . $e->getMessage());
    }
}


// Grab Total Pages
$total_pages = $gallery->total_pages($total_count, $per_page);


/* Grab the offset (page) location from using the offset method */
$offset = $gallery->offset($per_page, $current_page);

$links = new Links($current_page, $per_page, $total_count, $category);

$records = $gallery->page($per_page, $offset, 'gallery', $category);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">
    <style>

        #myButton {
            outline: none;
            color: #fff;
            border: none;
            background-color: #f12929;
            box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.5);
            width: 6.25em;
            font-family: "Rubik", sans-serif;
            font-size: 1.2em;
            text-transform: capitalize;
            text-decoration: none;
            cursor: pointer;
            padding: 0.313em;
            margin: 0.625em;
            transition: color 0.5s;
        }

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
            display: inline-block;
            padding: 6px 12px;
            color: #999;
            background-color: #fff;
            border: 1px solid #ddd;
            box-sizing: border-box;
            height: 2.313em;
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
            echo '<p class="home_paragraph">' . nl2br($record['content']) . '</p>';
            echo '<a id="myButton" href="delete.php?id=' . $record['id'] . '" class="delete-link">Delete</a>';


            echo '</div>';

        }
        ?>
    </div>
    <div class="home_sidebar">
        <form action="/dashboard..php" method="GET">
            <label for="category"></label>
            <select id="category" class="select-css" name="category" onchange="this.form.submit()" tabindex="1">
                <option selected value="<?= $category ?>"><?= ucfirst($category) ?></option>
                <option value="general">General</option>
                <option value="landscape">Landscape</option>
                <option value="lego">LEGO</option>
                <option value="halloween">Halloween</option>
                <option value="wildlife">Wildlife</option>
            </select>

            <button type="submit" class="submit-btn" name="submit" tabindex="2">Submit</button>
        </form>
        <?php echo $links->display_links(); ?>
        <?php $database->showAdminNavigation(); ?>
    </div>
</div>


<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteLinks = document.querySelectorAll('.delete-link');

        deleteLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                if (confirm("Are you sure you want to delete this item?")) {
                    window.location.href = this.href;
                }
            });
        });
    });

</script>


<script src="assets/js/lightbox.js"></script>
</body>
</html>
