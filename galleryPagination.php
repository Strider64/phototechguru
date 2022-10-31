<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";

use PhotoTech\CMS;
use PhotoTech\Pagination_New as Pagination;


$database_data = [];
/*
 * The below must be used in order for the json to be decoded properly.
 */
try {
    $database_data = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
}

$per_page = (int) $database_data['per_page']; // Total number of records to be displayed:
$database_data['total_count'] = CMS::countAllPage($database_data['category']); // Total Records in the db table:

/* Send the 3 variables to the Pagination class to be processed */
$pagination = new Pagination((int)$database_data['current_page'], $per_page, $database_data['total_count']);

/* Grab the offset (page) location from using the offset method */
$database_data['offset'] = $pagination->offset();

output($database_data);

function output($output): void
{
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }

}
