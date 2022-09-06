<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;


if (!isset($_SESSION['last_login'])) {
header("Location: gallery.php");
exit();
}

if ($_SESSION['id'] != 2) {
header('Location: gallery.php');
exit();
}

/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');


/*
* Grab per_page and offset
*/
$data = json_decode(file_get_contents('php://input'), true);

try {
    $date_added = new DateTime($data['date_taken'], new DateTimeZone("America/Detroit"));
} catch (Exception $e) {
}
$send['date_taken'] = $date_added->format('Y-m-d H:i:s');
$send['systolic'] = (int) $data['systolic'];
$send['diastolic'] = (int) $data['diastolic'];
$send['pulse'] = (int) $data['pulse'];
$send['miles_walked'] = (float) $data['miles_walked'];
$send['weight'] = (int) $data['weight'];
$send['sodium'] = (int) $data['sodium'];

$save = new Measure($send);
$save->create();


output(true);

/*
 * Throw error if something is wrong
 */

function errorOutput($output, $code = 500) {
    http_response_code($code);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }
}

/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output) {
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }
}