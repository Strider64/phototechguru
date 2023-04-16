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
use PhotoTech\LoginRepository as Login;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

// New Instance of Login Class
$login = new Login($pdo);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallery</title>
    <style>
        .sidebar_pages {
            display: flex;
            -webkit-flex-wrap: wrap;
            flex-wrap: wrap;
            justify-content: flex-start;
            height: auto;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">


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
        <?php $login->show_logoff_nav_button(); ?>
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

            <form id="gallery_category" action="gallery.php" method="post">
                <label for="category"></label><select id="category" class="select-css" name="category"
                                                                       tabindex="1">
                    <option selected disabled>Select a Category</option>
                    <option value="general" selected>General</option>
                    <option value="wildlife">Wildlife</option>
                    <option value="landscape">Landscape</option>
                    <option value="lego">LEGO</option>
                    <option value="halloween">Halloween</option>
                </select>
            </form>

            <div class="sidebar_pages">

            </div>
        </div>
    </div>

</main>
<aside class="sidebar">

</aside>
<div class="lightbox">

</div>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>

<script src="assets/js/images.js"></script>
<!--

 Copyright (c) 2022 by Annastasshia (https://codepen.io/annastasshia/pen/YzpEajJ)

 Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


-->
</body>
</html>
