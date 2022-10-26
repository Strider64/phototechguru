<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

/*
 * The Photo Tech Guru
 * Created by John R. Pepp
 * Date Created: July, 12, 2021
 * Last Revision: September 30, 2022 @ 2:17 PM
 * Version: 3.01 ßeta
 * Big Time Credit goes to
 * Annastasshia for the gallery design
 * https://codepen.io/annastasshia
 * without her contribution and
 * kindness of sharing this gallery
 * page would not be what it is.
 *
 */

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;

$category = 'general';
$displayFormat = ["gallery-container w-4 h-2", 'gallery-container w-3 h-2', 'gallery-container w-4 h-2', 'gallery-container w-3 h-2', 'gallery-container w-3 h-2', 'gallery-container w-2 h-2"', 'gallery-container h-2', 'gallery-container h-2', 'gallery-container w-2 h-2', 'gallery-container h-2', 'gallery-container w-2 h-2', 'gallery-container w-4 h-2'];
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
$total_count = CMS::countAllPage('blog', $category); // Total Records in the db table:


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
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallery</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">
    <style>
        /* pagination */
        .flex_container {
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
            align-items: stretch;
            align-content: center;
            width: 18.75em;
        }

        .flex_container a {
            padding: 0.625em;
            margin: 0.625em;
            text-align: center;
            text-decoration: none;
            margin-top: 2.500em;
        }

        .flex-item {
            color: #000;
        }

        .selected {
            color: #2e6b31;
        }

        .dashes {
            pointer-events: none;
        }

        p {
            font-size: 1.2em;
            line-height: 0.8;
            font-weight: bold;
        }

        .previous {
            /* Not Growable, Shrinkable and Init Width */
            flex: 0 0 4em;
        }
    </style>

</head>
<body class="site">
<!--
 * Big Time Credit goes to
 * Annastasshia for the gallery design
 * https://codepen.io/annastasshia
 * without her contribution and
 * kindness of sharing this on
 * Codepen this page would not be what it is.

 Copyright (c) 2022 by Annastasshia (https://codepen.io/annastasshia/pen/YzpEajJ)

 Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


-->
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
            <div class="container">

            </div>
        </div>
        <div class="home_sidebar">
            <div class="flex_container">
                <?= $pagination->links('gallery.php'); ?>
            </div>
            <form id="gallery_category" action="gallery.php" method="post">
                <label for="category">Select a Category</label><select id="category" class="select-css" name="category" tabindex="1">
                    <option selected disabled>Select a Category</option>
                    <option value="general" selected>General</option>
                    <option value="halloween">Halloween</option>
                    <option value="landscape">Landscape</option>
                    <option value="wildlife">Wildlife</option>
                </select>
            </form>
        </div>
    </div>

</main>
<aside class="sidebar">

</aside>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>

<script src="assets/js/images.js"></script>
</body>
</html>
