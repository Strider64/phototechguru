<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;


$data = [];
/*
 * The below must be used in order for the json to be decoded properly.
 */
try {
    $data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}


if (isset($_SESSION['category']) && $data['category'] === $_SESSION['category']) {
    $_SESSION['current_page'] += 1;
}  else {
    unset($_SESSION['category']);
    $_SESSION['category'] = $data['category'];
    $_SESSION['current_page'] = 1;
}


$per_page = 12; // Total number of records to be displayed:
$total_count = CMS::countAllPage('blog', $data['category']); // Total Records in the db table:

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination($_SESSION['current_page'], $per_page, $total_count);

/* Grab the offset (page) location from using the offset method */
$offset = $pagination->offset();

/*
 * Grab the data from the CMS class method *static*
 * and put the data into an array variable.
 */
$cms = CMS::page($per_page, $offset, 'blog', $data['category']);
output($cms);

function output($output): void
{
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }

}

