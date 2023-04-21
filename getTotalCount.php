<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";


use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\ImageContentManager;


$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();

$args = [];
$gallery = new ImageContentManager($pdo, $args);

function main(): array {
    return json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
}

$database_data = []; // Set a default value for $database_data
$database_data = main(); // Call the function to execute your code and get the decoded JSON data

$database_data['total_count'] = $gallery->countAllPage($database_data['category']); // Total Records in the db table:

output($database_data);

function output($output): void
{
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }

}