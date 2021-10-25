<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\Pagination_Links;

/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');

/*
 * The below must be used in order for the json to be decoded properly.
 */
try {
    $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}

$total_pages = Pagination_Links::total_pages($data['per_page']);
//$data['current_page'] += 1;


    $records = [
        'current_page' => $data['current_page'],
        'total_pages' => $total_pages
    ];





output($records);

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