<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Flickr Gallery</title>
    <link rel="stylesheet" media="all" href="assets/css/styles.css">
</head>
<body class="site">
<header class="header">
    <?php if (!isset($_SESSION['last_login'])) { ?>
        <form class="myForm" method="post">

            <label class="text_username" for="username">Username</label>
            <input id="username" class="io_username" type="text" name="user[username]" value="" required>


            <label class="text_password" for="password">Password</label>
            <input id="password" class="io_password" type="password" name="user[hashed_password]" required>


            <button class="submitBtn" id="submitForm" type="submit" name="submit" value="login">Login</button>

        </form>
    <?php } ?>
</header>
<?php include_once "assets/includes/inc.nav.php"; ?>
<main class="content">
    <a data-flickr-embed="true" href="https://www.flickr.com/photos/pepster/albums/72177720298520155" title="Photo Tech Guru"><img src="https://live.staticflickr.com/65535/52038943943_4c65ee1387_b.jpg" width="1024" height="768" alt="Photo Tech Guru"></a><script async src="//embedr.flickr.com/assets/client-code.js" charset="utf-8"></script>
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
                    <img src="assets/images/img-facebook-group-002.jpg" alt="FaceBook Group">
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
<script src="assets/js/lightbox.js"></script>
</body>
</html>