<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;
use PhotoTech\Pagination_New as Pagination;

if (!isset($_SESSION['last_login'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['id'] != 2) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bp_data = $_POST['data'];
    $bp_data['miles_walked'] = (float)$bp_data['miles_walked'];
    $bp_data['sodium'] = (int)$bp_data['sodium'];
    $bp_data['weight'] = (int)$bp_data['weight'];
    $measure = new Measure($bp_data);
    $measure->create();

}

function styleDate($prettyDate): string
{

    try {
        $dateStylized = new DateTime($prettyDate, new DateTimeZone("America/Detroit"));
    } catch (Exception $e) {
    }

    return $dateStylized->format("F j, Y");
}

$user_id = 2;

$per_page = 14; // Total number of records to be displayed:
$total_count = Measure::countByUser($user_id);

//echo $total_count . "<br>";

$current_page = $_GET['page'] ?? 1;

//echo $current_page . "<br>";

if (($current_page < 1)) {
    $current_page = 1;
} elseif ($current_page > ceil($total_count / $per_page)) {
    $current_page = ceil($total_count / $per_page);
}

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($current_page, $per_page, $total_count);

/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();

//echo 'Offset = ' . $offset . "<br>";

$bp = Measure::records($per_page, $offset);

//echo "<pre>" . print_r($bp, 1) . "</pre>";


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blood Pressure</title>
    <link href="https://fonts.googleapis.com/css?family=Unkempt" rel="stylesheet">
    <link rel="stylesheet" media="all" href="assets/css/styles.css">
    <style>

        .bpForm {
            display: grid;
            grid-template-columns: [labels] auto [controls] 1fr;
            grid-auto-flow: row;
            grid-gap: .8em;
            background: #eee;
            padding: 1.2em;
        }

        .bpForm > label {
            grid-column: labels;
            grid-row: auto;
            display: flex;
            justify-content: end;
            font: 1.0em 'Unkempt', sans-serif;
            line-height: 3.125em;
        }

        .bpForm > input,
        .bpForm > textarea,
        .bpForm > button {
            grid-column: controls;
            grid-row: auto;
            border: none;
            font: 1.2em 'Unkempt', sans-serif;
            font-weight: bold;
            padding: 1em;
        }

        .entries, .entry_headings {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            grid-auto-rows: auto;
            gap: 0.625em 0;
            font-family: 'Unkempt', sans-serif;
            font-size: 1.0em;
            color: #ff0;
            padding: 0 1.250em;
            margin-top: 1.250em;
        }

        .entries > div, .entry_headings > div {
            background-color: #33579c;
        }

        .date_taken {
            grid-column: span 2;
            grid-row: span 1;
            display: flex;
            justify-content: right;
            padding: 0 0.625em;
        }

        .systolic {
            grid-column: span 1;
            display: flex;
            justify-content: center;
            padding: 0 0.625em;
        }

        .diastolic {
            grid-column: span 1;
            display: flex;
            justify-content: center;
            padding: 0 0.625em;
        }

        .pulse {
            grid-column: span 1;
            display: flex;
            justify-content: center;
            padding: 0 0.313em;
        }

        .miles {
            grid-column: span 2;
            display: flex;
            justify-content: center;
            padding: 0 0.625em;
        }

        .weight {
            grid-column: span 1;
            display: flex;
            justify-content: right;
            padding: 0 0.313em
        }

        .sodium {
            grid-column: span 1;
            display: flex;
            justify-content: right;
            padding: 0 0.625em;
        }

        p.totals {
            font: 1.0em 'Unkempt', sans-serif;
            color: #000;
            padding: 0 1.250em;
        }

    </style>
</head>
<body class="site" data-pages="<?= $total_count ?>">
<header class="header">

</header>
<?php include_once "assets/includes/inc.nav.php"; ?>

<main class="content">

    <div class="entry_headings">
        <div class="date_taken">Date Taken</div>
        <div class="systolic">Systolic</div>
        <div class="diastolic">Diastolic</div>
        <div class="pulse">Pulse</div>
        <div class="miles">Miles</div>
        <div class="weight">Weight</div>
        <div class="sodium">Sodium</div>
    </div>
    <div class="entries">

    </div>

    <div class="flex_container" data-role="controlgroup">

    </div>

    <p class="totals">Total distance walked so far is <?= $total_distance = Measure::totalMiles($_SESSION['id']) ?>
        miles</p>

</main>

<div class="sidebar">
    <form class="bpForm" method="post">
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
</body>
</html>
