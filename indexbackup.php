<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

/*
 *  The Photo Tech Guru Website Version
 *  by John R. Pepp
 *  Started: February 1, 2020
 *  Revised: June 8, 2022
 */

use PhotoTech\CMS;
use PhotoTech\Pagination as Pagination;
use PhotoTech\LoginRepository;

if (isset($_POST['submit'])) {
    $login = new LoginRepository($_POST['user']);
    $login->login();
}
/*
 * Using pagination in order to have a nice looking
 * website page.
 */

$per_page = 1; // Total number of records to be displayed:
$total_count = CMS::countAllPage('blog'); // Total Records in the db table:
$current_page = $_GET['page'] ?? 1;

if (($current_page < 1)) {
    $current_page = 1;
} elseif ($current_page > ceil($total_count / $per_page)) {
    $current_page = ceil($total_count / $per_page);
}

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
//echo "<pre>" . print_r($cms, 1) . "</pre>";
//die();


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>The Photo Tech Guru</title>
    <link rel="stylesheet" media="all" href="assets/css/styles.css">
    <style>
        .myForm {
            display: grid;
            grid-template-columns: [labels] auto [controls] 1fr;
            grid-auto-flow: row;
            grid-gap: .8em;
            height: 6.250em;
            background-color: rgba(0, 0, 0, .40);
            padding: 1.2em;
        }

        .myForm > label {
            grid-column: labels;
            grid-row: auto;
            height: 25px;
            color: #fff;
        }

        .myForm > input,
        .myForm > textarea,
        .myForm > button {
            grid-column: controls;
            grid-row: auto;
            border: none;
            height: 25px;
        }

    </style>
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

    <?php foreach ($cms as $record) { ?>
        <article class="cms">

            <h2><?= $record['heading'] ?></h2>
            <span class="author_style">Created by <?= $record['author'] ?> on
                    <time datetime="<?= htmlspecialchars(CMS::styleTime($record['date_added'])) ?>"><?= htmlspecialchars(CMS::styleDate($record['date_added'])) ?></time>
                </span>
            <p><?= $record['content'] ?></p>
        </article>
    <?php } ?>

    <div class="flex_container">
        <?= $pagination->links(); ?>
    </div>
</main>

<div class="sidebar">

</div>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>
<script>
    let loginForm = document.querySelector('.myForm');
    loginForm.style.display = 'none';
    document.addEventListener('keydown', function (event) {
        event.preventDefault();

        if (event.key === 'h') {
            loginForm.style.display = 'none';
        }

        if (event.key === 'o') {
            loginForm.style.display = 'grid';
        }
    });
</script>
</body>
</html>
