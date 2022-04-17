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



/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');

$user_id = 2;

$per_page = 5; // Total number of records to be displayed:
$total_count = Measure::countByUser($user_id);

//$_SESSION['current_page'] = 2;

$data = json_decode(file_get_contents('php://input'), true);

$current_page = $data['current'];

if (($current_page < 1)) {
    $current_page = 1;
} elseif ($current_page > ceil($total_count / $per_page)) {
    $current_page = ceil($total_count / $per_page);
}

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($current_page, $per_page, $total_count);

/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();

$bp = Measure::records($per_page, $offset);

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