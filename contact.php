<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";
use PhotoTech\{
    ErrorHandler,
    Database,
};

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" media="all" href="assets/css/stylesheet.css">


</head>
<body class="site">
<header class="nav">
    <input type="checkbox" class="nav-btn" id="nav-btn">
    <label for="nav-btn">
        <span></span>
        <span></span>
        <span></span>
    </label>

    <nav class="nav-links" id="nav-links">
        <?php $database->regular_navigation(); ?>
    </nav>

    <div class="name-website">
        <h1 class="webtitle">The Photo Tech Guru</h1>
    </div>
</header>
<main class="content">
    <div class="main_container">
        <div class="home_article">
            <form class="contact" name="contact" action="contact.php" method="post" autocomplete="on">

                <!--                <input id="token" type="hidden" name="token" value="--><!--">-->
                <input type="hidden" name="reason" value="message">
                <figure class="owner">
                    <img src="assets/images/img-john-pepp-150-150-001.jpg" alt="John Pepp" width="150" height="150">
                    <figcaption>John Pepp</figcaption>
                </figure>
                <hr class="horizontal_line">
                <div class="contact_name">
                    <label class="labelstyle" for="name" accesskey="U">Contact Name</label>
                    <input name="name" type="text" id="name" tabindex="1" placeholder="Full Name" autofocus
                           required="required">
                </div>

                <div class="contact_email">
                    <label class="labelstyle" for="email" accesskey="E">Email</label>
                    <input name="email" type="email" id="email" placeholder="Email" tabindex="2" required="required">
                </div>

                <div class="contact_phone">
                    <label class="labelstyle" for="phone" accesskey="P">Phone <small>(optional)</small></label>
                    <input name="phone" type="tel" id="phone" tabindex="3">
                </div>

                <div class="contact_website">
                    <label class="labelstyle" for="web" accesskey="W">Website <small>(optional)</small></label>
                    <input name="website" type="text" id="web" tabindex="4">
                </div>


                <div class="contact_comment">
                    <label class="textareaLabel" for="comments">Comments Length:<span id="length"></span></label>
                    <textarea name="comments" id="comments" spellcheck="true" placeholder="Your Message" tabindex="6"
                              required="required"></textarea>
                </div>

                <div id="recaptcha" class="g-recaptcha" data-sitekey="6Le0QrobAAAAAGDacgiAr1UbkPmj0i-LFyWXocfg"
                     data-callback="correctCaptcha"></div>

                <div id="message">
                    <img class="notice" src="assets/images/email.png" alt="email icon">
                    <img class="pen" src="assets/images/fountain-pen-close-up.png" alt="fountain pen">
                </div>

                <button id="submitForm" class="submit_comment" type="submit" name="submit" value="Submit" tabindex="7"
                        data-response="">Submit
                </button>
                <!-- Use a data callback function that Google provides -->
            </form>
        </div>
        <div class="home_sidebar">
            <div id="successMessage" class="successStyle" style="display: none;">Your email has been sent successfully!</div>
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
    </div>
</main>

<div class="sidebar">

</div>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru - <span class="freepik_icons">Icons made by <a
                    href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/"
                                                                                       title="Flaticon">www.flaticon.com</a></span>
    </p>

</footer>

<script src="assets/js/contact.js"></script>
</body>
</html>