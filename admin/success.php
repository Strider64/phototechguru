<?php

require_once "../assets/config/config.php";
require_once "../vendor/autoload.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Successful Registration</title>
    <link rel="stylesheet" media="all" href="../assets/css/game.css">
</head>
<body class="site">
<div id="skip"><a href="#content">Skip to Main Content</a></div>
<header class="masthead">

</header>

<div class="nav">
    <input type="checkbox" id="nav-check">

    <h3 class="nav-title">
        The Photo Tech Guru
    </h3>

    <div class="nav-btn">
        <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
        </label>
    </div>

    <div class="nav-links">
        <a href="../photogallery.php">Home</a>
    </div>
</div>

<main id="content" class="checkStyle">
    <h2>Thank You for Registering!</h2>
    <p>There was an email sent to your email address that you need to activate your account in order to be granted full privileges. Please check you junk or spam folder if you haven't received it. I look forward in you visiting the new improved Miniature Photographer website.</p>
</main>
<div class="sidebar">

</div>
<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>

</body>
</html>

