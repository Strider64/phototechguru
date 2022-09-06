<?php
require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Measure;



/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');


/*
 * Grab per_page and offset
 */
$data = json_decode(file_get_contents('php://input'), true);


$bp = Measure::records($data['per_page'], $data['offset']);




    for ($x=0; $x < count($bp); $x++) {
        $temp = new DateTime($bp[$x]['date_taken'], new DateTimeZone("America/Detroit"));
        $bp[$x]['date_taken'] = $temp->format('F j, Y');

    }







if (isset($bp)) {
    output($bp);
}

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