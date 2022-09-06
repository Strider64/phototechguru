<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;

use PhotoTech\Pagination_New as Pagination;

$perPage = $totalCount = Measure::countByUser(2);

$currentPage = $_GET['page'] ?? 1;

if (($currentPage < 1)) {
    $currentPage = 1;
} elseif ($currentPage > ceil($totalCount / $perPage)) {
    $currentPage = ceil($totalCount / $perPage);
}

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($currentPage, $perPage, $totalCount);

/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();

$records = Measure::records($perPage, $offset);

//echo "<pre>" . print_r($records, 1) . "</pre>";
//die();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=yes, initial-scale=1.0">
    <title>Print Blood Pressure</title>
    <link rel="stylesheet" media="all" href="assets/css/bpCSS.css">
</head>
<body>
<header class="header">
    <h2>Blood Pressure Data</h2>
</header>
<main>
    <div class="table-wrapper" tabindex="0">
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Systolic</th>
                <th>Diastolic</th>
                <th>Pulse</th>
                <th>Miles Walked</th>
                <th>Weight</th>
                <th>Sodium</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $totalSystolic = 0;
            $totalDiastolic = 0;
            $totalPulse = 0;
            $totalMiles = 0;

            $x = 1;
            foreach ($records as $record) {
                echo "<tr>" . "\n";
                echo "<td>" . htmlspecialchars(Measure::bpDate($record['date_taken'])) . "</td>\n";

                $x++;
                echo "<td>" . $record['systolic'] . "</td>\n";
                $totalSystolic = $totalSystolic + $record['systolic'];
                echo "<td>" . $record['diastolic'] . "</td>\n";
                $totalDiastolic = $totalDiastolic + $record['diastolic'];
                echo "<td>" . $record['pulse'] . "</td>\n";
                $totalPulse = $totalPulse + $record['pulse'];
                echo "<td>" . $record['miles_walked'] . "</td>\n";
                $totalMiles = $totalMiles + $record['miles_walked'];
                echo "<td>" . $record['weight'] . "</td>\n";
                echo "<td>" . $record['sodium'] . "</td>\n";
                echo "</tr>\n";
            }

            ?>
            </tbody>
            <tfoot>
            <?php
                echo "<tr>";
                echo "<td>Averages</td>";
                echo "<td>&nbsp;</td>";
                echo "<td>" . round($totalSystolic / count($records)) . "</td>";
                echo "<td>" . round($totalDiastolic / count($records)) . "</td>";
                echo "<td>" . round($totalPulse / count($records)) . "</td>";
                echo "<td>" . $totalMiles . " total</td>";
                echo "</tr>";
            ?>
            </tfoot>
        </table>
    </div>


    <footer>

    </footer>
</body>
</html>
