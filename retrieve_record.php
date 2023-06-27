<?php

require_once 'assets/config/config.php';
require_once "vendor/autoload.php";


use PhotoTech\ErrorHandler;
use PhotoTech\Database;
use PhotoTech\TriviaDatabaseOBJ as Trivia;

$errorHandler = new ErrorHandler();

// Register the exception handler method
set_exception_handler([$errorHandler, 'handleException']);

$database = new Database();
$pdo = $database->createPDO();
// Check to see if user is Logged In
if (!$database->check_login_token()) {
    header('location: index.php');
    exit();
}
$trivia = new Trivia($pdo);


$data = $trivia->fetchAllQuestions();

/* Makes it, so we don't have to decode the json coming from javascript */
header('Content-type: application/json');


output($data);


/*
 * After converting data array to JSON send back to javascript using
 * this function.
 */
function output($output)
{
    http_response_code(200);
    try {
        echo json_encode($output, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
    }
}
