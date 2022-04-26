<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;


if (!isset($_SESSION['last_login'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['id'] != 2) {
    header('Location: index.php');
    exit();
}

/*
 * Add a new record to the database table.
 */
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    $bp_data = $_POST['data'];
//    $bp_data['miles_walked'] = (float)$bp_data['miles_walked'];
//    $bp_data['sodium'] = (int)$bp_data['sodium'];
//    $bp_data['weight'] = (int)$bp_data['weight'];
//    $measure = new Measure($bp_data);
//    $measure->create();
//
//}


$user_id = $_SESSION['id']; // Get User Id:


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blood Pressure</title>
    <link rel="stylesheet" media="all" href="assets/css/bpStyle.css">
    <style>



    </style>
</head>
<body class="site" data-pages="<?=  Measure::countByUser($user_id) ?>">
<header class="header">

</header>

<?php include_once "assets/includes/inc.nav.php"; ?>

<main class="content">



    <ul class="cards">

    </ul>

    <div class="flex_container" data-role="controlgroup">

    </div>

    <p class="totals">Total distance walked so far is <?= $total_distance = Measure::totalMiles($_SESSION['id']) ?>
        miles</p>

</main>

<div class="sidebar">
    <form id="bpForm" class="bpForm" method="post">
        <label for="date_taken_form">Date Taken</label>
        <input type="date" name="data[date_taken]" id="date_taken_form" tabindex="1" required>
        <label for="systolic_form">Systolic</label>
        <input type="text" name="data[systolic]" id="systolic_form" tabindex="2" required>
        <label for="diastolic_form">Diastolic</label>
        <input type="text" name="data[diastolic]" id="diastolic_form" tabindex="3" required>
        <label for="pulse_form">Pulse</label>
        <input type="text" name="data[pulse]" id="pulse_form" tabindex="4" required>
        <label for="miles_walked_form">Miles</label>
        <input type="text" name="data[miles_walked]" id="miles_walked_form" value="0" tabindex="5">
        <label for="weight_form">Weight</label>
        <input type="number" name="data[weight]" id="weight_form" value="0" tabindex="6">
        <label for="sodium_form">Sodium</label>
        <input type="text" name="data[sodium]" id="sodium_form" value="0" tabindex="7">
        <button class="bpButton" type="submit" name="submit" value="enter" tabindex="8">Submit</button>
    </form>
</div>

<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>


<script src="assets/js/bp.js" async defer></script>
<script src="assets/js/bp_new.js" async defer></script>
<script src="assets/js/bp_edit.js" async defer></script>


</body>
</html>
