<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;

$user_id = 2;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">

    <title>Blood Pressure Readings</title>
    <link rel="stylesheet" media="all" href="assets/css/bpStyle.css">
</head>
<body class="site" data-pages="<?=  Measure::countByUser($user_id) ?>">
<header class="header">

</header>

<?php include_once "assets/includes/inc.nav.php"; ?>
<div class="sidebar">
    <p class="totals">Total distance walked so far is <?= $total_distance = Measure::totalMiles(2) ?> miles</p>
    <p class="totals">Average systolic is <?= Measure::systolic() ?></p>
    <p class="totals">Average diastolic is <?= Measure::diastolic() ?></p>
    <p class="totals">Average pulse is <?= Measure::pulse() ?></p>
</div>
<main class="content">

    <ul class="cards">

    </ul>

    <div class="flex_container" data-role="controlgroup">

    </div>

</main>



<footer class="colophon">
    <p>&copy; <?php echo date("Y") ?> The Photo Tech Guru</p>
</footer>


<script src="assets/js/bp.js" async defer></script>


</body>
</html>