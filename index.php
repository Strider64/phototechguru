<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

/*
 * The Photo Tech Guru
 * Created by John R. Pepp
 * Date Created: July, 12, 2021
 * Last Revision: April 20, 2023
 * Version: 5.01 ßeta
 *
 */
use PhotoTech\{
    ErrorHandler,
    Database,
    Links,
    ImageContentManager
};

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

$args = [];
// New Instance of CMS Class

$gallery = new ImageContentManager($pdo, $args);


if ($database->check_login_token()) {
    header('location: dashboard.php');
    exit();
}


$displayFormat = ["gallery-container w-2 h-2", 'gallery-container w-2 h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2"', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2'];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
    } else {
        error_log('Category is not set in the GET data');
        $category = 'general';
    }
    $total_count = $gallery->countAllPage($category);
} else {
    try {
        $category = 'wildlife';
        $total_count = $gallery->countAllPage($category);
    } catch (Exception $e) {
        error_log('Error while counting all pages: ' . $e->getMessage());
    }
}


/*
 * Using pagination in order to have a nice looking
 * website page.
 */

// Grab the current page the user is on
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $current_page = urldecode($_GET['page']);
} else {
    $current_page = 1;
}

$per_page = 1; // Total number of records to be displayed:


// Grab Total Pages
$total_pages = $gallery->total_pages($total_count, $per_page);


/* Grab the offset (page) location from using the offset method */
/* $per_page * ($current_page - 1) */
$offset = $gallery->offset($per_page, $current_page);

// Figure out the Links that you want the display to look like
$links = new Links($current_page, $per_page, $total_count, $category);

// Finally grab the records that are actually going to be displayed on the page
$records = $gallery->page($per_page, $offset, 'gallery', $category);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Home Page</title>
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
            transition: background-color 0.5s;
            float: right;
            text-align: center;
        }

        #myButton:hover {
            background-color: #009578;
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
        }



    </style>


</head>
<body class="site">

<div class="nav">


    <div class="nav-btn" onclick="toggleNavMenu()">
        <label>
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>


    <div class="nav-links" id="nav-links">
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
            echo '</div>';

        }
        ?>

    </div>
    <div class="home_sidebar">
        <form id="myForm" action="index.php" method="GET">
            <label for="category"></label>
            <select id="category" class="select-css" name="category" tabindex="1">
                <option selected value="<?= $category ?>"><?= ucfirst($category) ?></option>
                <option value="general">General</option>
                <option value="landscape">Landscape</option>
                <option value="lego">LEGO</option>
                <option value="halloween">Halloween</option>
                <option value="wildlife">Wildlife</option>
            </select>

            <button style="display: none" type="submit" class="submit-btn" name="enter" tabindex="2">Submit</button>
        </form>

        <?php echo $links->display_links(); ?>
    </div>
</div>


<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script>
    const selectElement = document.getElementById("category");

    selectElement.addEventListener("change", (event) => {
        // Code to handle the change event
        event.target.form.submit();
    });
</script>
<script src="assets/js/lightbox.js"></script>
<script>
    function toggleNavMenu() {
        let navLinks = document.getElementById('nav-links');
        if (navLinks.style.height === '0px' || navLinks.style.height === '') {
            navLinks.style.height = 'calc(100vh - 3.125em)';
            navLinks.style.overflowY = 'auto';
        } else {
            navLinks.style.height = '0px';
            navLinks.style.overflowY = 'hidden';
        }
    }
</script>

</body>
</html>
